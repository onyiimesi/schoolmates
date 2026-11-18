<?php

namespace App\Http\Controllers;

use App\Enum\PeriodicName;
use App\Enum\StudentStatus;
use App\Models\GradingSystem;
use App\Models\Result;
use App\Models\Staff;
use App\Models\Student;
use App\Traits\HttpResponses;
use App\Traits\ResultTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BroadSheetController extends Controller
{
    use HttpResponses, ResultTrait;

    private ?Collection $gradingSystems = null;

    public function broadsheet(Request $request)
    {
        $user = Auth::user();

        $sheet = Result::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->whereClass('class_name', $request->class_name)
            ->where('term', $request->term)
            ->whereIn('period', [
                PeriodicName::FIRSTHALF,
                PeriodicName::SECONDHALF,
            ])
            ->where('session', $request->session)
            ->with('studentScores', 'student')
            ->get();

        $groupedResults = $sheet->groupBy('student_id');

        // The live roster defines class membership for the current session.
        // Past sessions are built from the result rows recorded for that class
        // and session. Students who have since moved to another class are not
        // merged into the current roster.
        $currentSession = currentAcademicSession($user);
        $isCurrentSession = $currentSession === null
            || sameAcademicSession($currentSession, $request->session);

        $students = collect();

        if ($isCurrentSession) {
            // Current-session broadsheets start with the live roster so students
            // without results still appear.
            $students = Student::query()
                ->where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->whereClass('present_class', $request->class_name)
                ->where('status', StudentStatus::ACTIVE)
                ->get(['id', 'surname', 'firstname', 'middlename', 'present_class']);
        }

        $signatures = Staff::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->whereClass('class_assigned', $request->class_name)
            ->get();

        $data = $this->getBroadsheetData(
            $students,
            $groupedResults,
            !$isCurrentSession,
        );

        return response()->json(
            [
                'status' => true,
                'message' => 'Broadsheet',
                'class_name' => $request->class_name,
                'current_session' => $currentSession,
                'is_current_session' => $isCurrentSession,
                'data' => $data,
                'teacher' => $signatures
                    ->map(function ($teacher) {
                        return [
                            'name' => "{$teacher->surname} {$teacher->firstname}",
                            'signature' => $teacher->signature,
                        ];
                    })
                    ->toArray(),
            ],
            200,
        );
    }

    private function getBroadsheetData(
        $students,
        $groupedResults,
        bool $includeResultOnlyStudents = true,
    ) {
        // Start with the class roster so students without results still appear.
        $entries = $students->mapWithKeys(function (Student $student) {
            return [
                (string) $student->id => [
                    'student_id' => (string) $student->id,
                    'class_name' => $student->present_class,
                    'present_class' => $student->present_class,
                    'student_fullname' => trim(
                        "{$student->surname} {$student->firstname} {$student->middlename}"
                    ),
                ],
            ];
        });

        // Preserve students who only exist in results (past sessions). For the
        // current session the roster above is the complete class membership.
        if ($includeResultOnlyStudents) {
            foreach ($groupedResults as $studentId => $studentResults) {
                $studentId = (string) $studentId;

                if ($entries->has($studentId)) {
                    continue;
                }

                $result = $studentResults->first();

                $entries->put($studentId, [
                    'student_id' => $studentId,
                    'class_name' => normalizeClassName($result->class_name),
                    // The student's class today. This differs from class_name
                    // when the student has since been promoted.
                    'present_class' => normalizeClassName($result->student?->present_class),
                    'student_fullname' => $result->student_fullname,
                ]);
            }
        }

        $data = $entries
            ->map(function (array $student, $studentId) use ($groupedResults) {
                return $this->calculateStudentSummary(
                    $groupedResults->get((string) $studentId, collect()),
                    $student,
                );
            })
            ->values();

        return $this->assignPositions($data)->toArray();
    }

    private function assignPositions($data): Collection
    {
        $sorted = $data
            ->sortByDesc(fn ($s) => (float) $s['student_average'])
            ->values();

        $rank = 1;
        $tieCount = 0;
        $prevAverage = null;

        return $sorted->map(function ($student) use (&$rank, &$tieCount, &$prevAverage) {
            $average = $student['student_average']; // already a formatted string e.g. "85.50"

            if ($average !== $prevAverage) {
                $rank += $tieCount; // advance past the last tie group
                $tieCount = 1;
                $prevAverage = $average;
            } else {
                $tieCount++; // same average, widen the tie group
            }

            $student['position'] = $rank;

            return $student;
        });
    }

    private function calculateStudentSummary($studentResults, array $student)
    {
        // Calculate average — use the already-eager-loaded relation collection
        // directly to avoid any false N+1 concerns from static analysers.
        $subjectScores = [];
        $totalScore = 0;
        foreach ($studentResults as $result) {
            $scores = $result->getRelation('studentScores');
            foreach ($scores as $score) {
                if ($score->score != 0) {
                    // getAttribute() makes it explicit this is a DB column, not a relation,
                    // which silences false N+1 warnings from static analysers.
                    $subjectScores[$score->getAttribute('subject')] = true;
                    $totalScore += (int) $score->score;
                }
            }
        }

        $totalSubjects = count($subjectScores);
        $studentAverage = $totalSubjects > 0 ? $totalScore / $totalSubjects : 0;

        // Build results per subject
        $combinedScores = $this->buildSubjectScores($studentResults);

        return [
            'student_id' => $student['student_id'],
            'class_name' => $student['class_name'],
            'present_class' => $student['present_class'] ?? null,
            'student_fullname' => $student['student_fullname'],
            'results' => $combinedScores,
            'student_average' => number_format($studentAverage, 2),
            'grade' => $this->calculateGrade($studentAverage),
        ];
    }

    private function buildSubjectScores($studentResults)
    {
        return $studentResults
            ->flatMap(function ($result) {
                // Use getRelation() to access the already-eager-loaded collection
                // without triggering a lazy-load (silences N+1 static analysis warnings).
                return $result->getRelation('studentScores')->map(function ($score) use ($result) {
                    return [
                        // getAttribute() makes it explicit this is a DB column, not a relation,
                        // which silences false N+1 warnings from static analysers.
                        'subject'     => $score->getAttribute('subject'),
                        'period'      => $result->period,
                        'result_type' => $result->result_type,
                        'score'       => (int) $score->score,
                    ];
                });
            })
            ->groupBy(function ($score) {
                return strtolower($score['subject']);
            })
            ->map(function ($subjectScores) {
                $subjectName = $subjectScores->first()['subject'];

                // Sum all assessments
                $assessmentScore = collect($subjectScores)
                    ->whereIn('result_type', [
                        'first_assesment',
                        'second_assesment',
                        'third_assesment',
                        'midterm',
                    ])
                    ->sum('score');

                // Sum exam (endterm)
                $examScore = collect($subjectScores)
                    ->where('result_type', 'endterm')
                    ->sum('score');

                return [
                    'subject' => $subjectName,
                    'assessment_score' => $assessmentScore,
                    'exam_score' => $examScore,
                    'total_score' => $assessmentScore + $examScore,
                ];
            })
            ->values()
            ->toArray();
    }

    private function calculateGrade($average)
    {
        if ($this->gradingSystems === null) {
            $user = Auth::user();

            $this->gradingSystems = GradingSystem::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->get();
        }

        $average = round((float) $average, 2);

        // The score columns are stored as strings, so compare them numerically
        // in PHP rather than relying on the database's string comparison.
        $grade = $this->gradingSystems->first(function (GradingSystem $grading) use ($average) {
            return $average >= (float) $grading->score_from
                && $average <= (float) $grading->score_to;
        });

        if ($grade) {
            return $grade->remark;
        }

        return $average > 90 ? 'EXCELLENT' : '';
    }
}
