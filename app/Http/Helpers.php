<?php

use App\Enum\PeriodicName;
use App\Enum\StudentStatus;
use App\Models\AcademicPeriod;
use App\Models\AcademicSessions;
use App\Models\Result;
use App\Models\Stationary;
use App\Models\Student;
use GuzzleHttp\Client;
use Illuminate\Database\Events\QueryExecuted;
use ImageKit\ImageKit;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\ImageKit\ImageKitService;
use Illuminate\Http\UploadedFile;

if (!function_exists('defer_email')) {
    function defer_email($email, $action)
    {
        defer(function () use ($email, $action) {
            Mail::to($email)->send($action);
        });
    }
}

if (!function_exists('userAuth')) {
    function userAuth()
    {
        return Auth::user();
    }
}

if (!function_exists('getLocation')) {
    function getLocation($lat, $lon)
    {
        $client = new Client();

        $response = $client->get("https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}");
        $data = json_decode($response->getBody(), true);

        if (isset($data['display_name'])) {
            return $data['display_name'];
        } else {
            return 'Location not found';
        }
    }
}

if (!function_exists('getImageKit')) {
    function getImageKit()
    {
        return new ImageKit(
            config('services.imagekit.public_key'),
            config('services.imagekit.private_key'),
            config('services.imagekit.endpoint_key')
        );
    }
}


if (!function_exists('isDataImage')) {
    function isDataImage($payload): bool
    {
        return is_string($payload) && Str::startsWith($payload, 'data:image');
    }
}

if (!function_exists('mimeToExt')) {
    function mimeToExt(string $mime): string
    {
        // e.g. image/jpeg -> jpg
        $type = strtolower(explode('/', $mime, 2)[1] ?? 'jpeg');
        return $type === 'jpeg' ? 'jpg' : $type;
    }
}

if (!function_exists('parseDataImage')) {
    /**
     * @return array{mime:string, ext:string, base64:string}|null
     */
    function parseDataImage(string $payload): ?array
    {
        if (!isDataImage($payload)) {
            return null;
        }

        // Expect "data:image/<ext>;base64,<data>"
        if (!preg_match('#^data:(image/[-+\w.]+);base64,#i', $payload, $m)) {
            return null;
        }

        $mime = $m[1];
        $ext  = mimeToExt($mime);

        // Split on the first comma only; guard missing comma
        $pos = strpos($payload, ',');
        if ($pos === false) {
            return null;
        }

        return [
            'mime'   => $mime,
            'ext'    => $ext,
            'base64' => substr($payload, $pos + 1),
        ];
    }
}

if (!function_exists('uploadImage')) {
    function uploadImage($file, $folder, $schId, $fileId = null): ?array
    {
        $result = null;
        $folderName = "{$folder}/{$schId}";
        $folderPath = null;
        $payload = null;

        // Base64 Image
        if (is_string($file) && Str::startsWith($file, 'data:image')) {
            $parsed = parseDataImage($file);

            if ($parsed) {
                $folderPath = time() . '.' . $parsed['ext'];
                $payload = $file;
            }
        }

        // Uploaded File
        elseif ($file instanceof UploadedFile && $file->isValid()) {
            if (Str::startsWith($file->getMimeType(), 'image/')) {
                $extension = $file->getClientOriginalExtension() ?: $file->extension();
                $folderPath = time() . '.' . $extension;
                $payload = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
            }
        }

        // Process if valid
        if ($payload && $folderPath) {
            $result = (new ImageKitService($payload, $folderPath, $folderName, $fileId))->run();
        }

        return $result;
    }
}

if (!function_exists('uploadSignature')) {
    function uploadSignature($file, $folder, $schId, $fileId = null)
    {
        $parsed = is_string($file) ? parseDataImage($file) : null;
        if (!$parsed) {
            return null;
        }

        $file_name = uniqid() . '.' . $parsed['ext'];
        $folderPath = $file_name;
        $folderName = "{$folder}/{$schId}";

        return (new ImageKitService($file, $folderPath, $folderName, $fileId))->run();
    }
}

if (!function_exists('generateUniqueId')) {
    function generateUniqueId()
    {
        do {
            $id = 'STA' . strtoupper(Str::random(8));
        } while (Stationary::where('unique_id', $id)->exists());

        return $id;
    }
}

if (!function_exists('normalizeClassName')) {
    /**
     * Normalize a class name to the canonical DB format regardless of how it is sent.
     */
    function normalizeClassName(?string $className): ?string
    {
        if ($className === null) {
            return null;
        }

        $upper = strtoupper(trim($className));

        // Pattern: one or more letters, optional spaces, one or more digits,
        // optional trailing letters (e.g. "A" for a stream/arm)
        if (preg_match('/^([A-Z]+)\s*(\d+)\s*([A-Z]*)$/', $upper, $m)) {
            $letters = $m[1];   // e.g. "SS", "JSS"
            $number  = $m[2];   // e.g. "1", "2"
            $suffix  = $m[3];   // e.g. "A" or ""
            return $suffix !== ''
                ? "{$letters} {$number}{$suffix}"
                : "{$letters} {$number}";
        }

        // Not a recognisable class name pattern — return uppercased as-is.
        return $upper;
    }
}

if (!function_exists('classNameLookupKey')) {
    /**
     * Build a comparison key for class names so variants such as "SS 1",
     * "Ss1" and "ss 1" all resolve to the same value.
     */
    function classNameLookupKey(?string $className): ?string
    {
        $normalized = normalizeClassName($className);

        return $normalized === null ? null : str_replace(' ', '', $normalized);
    }
}

if (!function_exists('currentAcademicSession')) {
    /**
     * Resolve the session the school is currently operating in. The academic
     * period is the source of truth; older schools that have not set one yet
     * fall back to their latest configured academic session.
     */
    function currentAcademicSession($user): ?string
    {
        if (!$user) {
            return null;
        }

        $session = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('is_current_period', true)
            ->value('session');

        if ($session) {
            return $session;
        }

        // Some schools create the new session without marking a period as
        // current. The latest configured session is the best fallback.
        $latestSession = AcademicSessions::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->max('academic_session');

        if ($latestSession) {
            return $latestSession;
        }

        return AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->value('session');
    }
}

if (!function_exists('sameAcademicSession')) {
    /**
     * Compare two session labels, ignoring whitespace and casing.
     */
    function sameAcademicSession(?string $first, ?string $second): bool
    {
        if ($first === null || $second === null) {
            return false;
        }

        $normalize = fn (string $session) => strtoupper(
            preg_replace('/\s+/', '', $session)
        );

        return $normalize($first) === $normalize($second);
    }
}

if (!function_exists('studentsForClassSession')) {
    /**
     * Resolve class membership for a session. A current session uses the live
     * roster plus students whose result rows belong to the class that session;
     * a past session is built from its result rows so students who have since
     * been promoted keep their historical class.
     *
     * Current-class students stay first so callers that rely on the first row
     * keep their existing behaviour.
     *
     * @return \Illuminate\Support\Collection<int, \App\Models\Student>
     */
    function studentsForClassSession($user, ?string $class, ?string $session = null)
    {
        if (!$user || !$class) {
            return collect();
        }

        $currentSession = currentAcademicSession($user);
        $requestedSession = $session ?: $currentSession;
        $isCurrentSession = $currentSession === null
            || sameAcademicSession($currentSession, $requestedSession);

        $students = collect();

        if ($isCurrentSession) {
            $students = Student::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->whereClass('present_class', $class)
                ->where('status', StudentStatus::ACTIVE)
                ->get();
        }

        if ($requestedSession === null) {
            return $students;
        }

        $resultStudentIds = Result::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->whereClass('class_name', $class)
            ->where('session', $requestedSession)
            ->whereIn('period', [
                PeriodicName::FIRSTHALF,
                PeriodicName::SECONDHALF,
            ])
            ->whereNotIn('student_id', $students->pluck('id')->all())
            ->distinct()
            ->pluck('student_id');

        if ($resultStudentIds->isEmpty()) {
            return $students;
        }

        return $students->concat(
            Student::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->whereIn('id', $resultStudentIds)
                ->where('status', StudentStatus::ACTIVE)
                ->get()
        );
    }
}
