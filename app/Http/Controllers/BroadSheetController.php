<?php

namespace App\Http\Controllers;

use App\Enum\PeriodicName;
use App\Models\GradingSystem;
use App\Models\Staff;
use App\Models\Result;
use App\Models\Student;
use App\Traits\HttpResponses;
use App\Traits\ResultTrait;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BroadSheetController extends Controller
{
    use HttpResponses, ResultTrait;

    public function broadsheet(Request $request): JsonResponse
    {
        /** @var Staff|Student $user */
        $user = Auth::user();

        $sheet = Result::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $request->class_name)
            ->where('term', $request->term)
            ->whereIn('period', [PeriodicName::FIRSTHALF, PeriodicName::SECONDHALF])
            ->where('session', $request->session)
            ->with('studentScores', 'student')
            ->get();

        $groupedResults = $sheet->groupBy('student_id');
        $signatures = Staff::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_assigned', $request->class_name)->get();
        
        $data = $this->getBroadsheetData($groupedResults);

        return new JsonResponse([
            'status' => true,
            'message' => "Broadsheet",
            'class_name' => $request->class_name,
            'data' => $data,
            'teacher' => $signatures->map(function ($teacher) {
                return [
                    "name" => "{$teacher->surname} {$teacher->firstname}",
                    "signature" => $teacher->signature
                ];
            })->toArray()
        ], 200);
    }

    /**
     * @param  Collection<int|string, EloquentCollection<int, Result>> $groupedResults
     * @return array<int, array<string, mixed>>
     */
    private function getBroadsheetData($groupedResults): array
    {
        /** @var Collection<int, array<string, mixed>> $data */
        $data = $groupedResults->map(function ($studentResults, $studentId) {
            return $this->calculateStudentSummary($studentResults, $studentId);
        })->filter()->values();

        return $this->assignPositions($data)->all();
    }

    /**
     * @param  Collection<int, array<string, mixed>> $data
     * @return Collection<int, array<string, mixed>>
     */
    private function assignPositions(Collection $data): Collection
    {
        $sorted = $data->sortByDesc(fn($s) => (float) $s['student_average'])->values();

        $rank = 1;
        $tieCount = 0;
        $prevAverage = null;

        /** @var Collection<int, array<string, mixed>> $result */
        $result = $sorted->map(function (array $student) use (&$rank, &$tieCount, &$prevAverage): array {
            /** @var string $average */
            $average = $student['student_average'];

            if ($average !== $prevAverage) {
                $rank += $tieCount;
                $tieCount = 1;
                $prevAverage = $average;
            } else {
                $tieCount++;
            }

            $student['position'] = $rank;
            return $student;
        });

        return $result;
    }

    /**
     * @param  Collection<int, Result> $studentResults
     * @param  int|string $studentId
     * @return array<string, mixed>
     */
    private function calculateStudentSummary(Collection $studentResults, int|string $studentId): array
    {
        $student = $studentResults->first();

        // Calculate average
        $subjectScores = [];
        $totalScore = 0;
        foreach ($studentResults as $result) {
            foreach ($result->studentScores as $score) {
                $subjectScores[$score->subject] = true;
                $totalScore += (int) $score->score;
            }
        }

        $totalSubjects = count($subjectScores);
        $studentAverage = $totalSubjects > 0 ? $totalScore / $totalSubjects : 0;

        // Build results per subject
        $combinedScores = $this->buildSubjectScores($studentResults);

        return [
            'student_id' => $studentId,
            'class_name' => $student->class_name,
            'student_fullname' => $student->student_fullname,
            'results' => $combinedScores,
            'student_average' => number_format($studentAverage, 2),
            'grade' => $this->calculateGrade($studentAverage),
        ];
    }

    /**
     * @param  Collection<int, Result> $studentResults
     * @return array<int, array<string, mixed>>
     */
    private function buildSubjectScores(Collection $studentResults): array
    {
        return $studentResults->flatMap(function ($result) {
            return $result->studentScores->map(function ($score) use ($result) {
                return [
                    'subject' => $score->subject,
                    'period' => $result->period,
                    'result_type' => $result->result_type,
                    'score' => (int) $score->score,
                ];
            })->all();
        })
            ->groupBy(fn (array $score): string => strtolower($score['subject']))
            ->map(function ($subjectScores) {
                $subjectName = $subjectScores->first()['subject'];

                // Sum all assessments
                $assessmentScore = (new \Illuminate\Support\Collection($subjectScores))
                    ->whereIn('result_type', ['first_assesment', 'second_assesment', 'third_assesment', 'midterm'])
                    ->sum('score');

                // Sum exam (endterm)
                $examScore = (new \Illuminate\Support\Collection($subjectScores))
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
            ->all();
    }

    private function calculateGrade(float|int $average): string
    {
        if ($average > 90) {
            return "EXCELLENT";
        }

        $grade = GradingSystem::where('score_to', '>=', $average)->first();
        return $grade->remark ?? "";
    }
}
