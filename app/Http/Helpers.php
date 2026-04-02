<?php

use App\Models\Stationary;
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
                $payload = $file;
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
