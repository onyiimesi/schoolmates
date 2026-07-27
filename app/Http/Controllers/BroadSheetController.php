<?php

namespace App\Http\Controllers;

use App\Enum\PeriodicName;
use App\Models\GradingSystem;
use App\Models\Staff;
use App\Models\Result;
use App\Traits\HttpResponses;
use App\Traits\ResultTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BroadSheetController extends Controller
{
    use HttpResponses, ResultTrait;

    public function broadsheet(Request $request)
    {
        $user = Auth::user();

        $sheet = Result::where("sch_id", $user->sch_id)
            ->where("campus", $user->campus)
            ->where("class_name", $request->class_name)
            ->where("term", $request->term)
            ->whereIn("period", [
                PeriodicName::FIRSTHALF,
                PeriodicName::SECONDHALF,
            ])
            ->where("session", $request->session)
            ->with("studentScores", "student")
            ->get();

        $groupedResults = $sheet->groupBy("student_id");
        $signatures = Staff::where("sch_id", $user->sch_id)
            ->where("campus", $user->campus)
            ->where("class_assigned", $request->class_name)
            ->get();

        $data = $this->getBroadsheetData($groupedResults);

        return response()->json(
            [
                "status" => true,
                "message" => "Broadsheet",
                "class_name" => $request->class_name,
                "data" => $data,
                "teacher" => $signatures
                    ->map(function ($teacher) {
                        return [
                            "name" => "{$teacher->surname} {$teacher->firstname}",
                            "signature" => $teacher->signature,
                        ];
                    })
                    ->toArray(),
            ],
            200,
        );
    }

    private function getBroadsheetData($groupedResults)
    {
        $data = $groupedResults
            ->map(function ($studentResults, $studentId) {
                return $this->calculateStudentSummary(
                    $studentResults,
                    $studentId,
                );
            })
            ->filter()
            ->values();

        return $this->assignPositions($data)->toArray();
    }

    private function assignPositions($data): Collection
    {
        $sorted = $data
            ->sortByDesc(fn($s) => (float) $s["student_average"])
            ->values();

        $rank = 1;
        $tieCount = 0;
        $prevAverage = null;

        return $sorted->map(function ($student) use (&$rank, &$tieCount, &$prevAverage, ) {
            $average = $student["student_average"]; // already a formatted string e.g. "85.50"

            if ($average !== $prevAverage) {
                $rank += $tieCount; // advance past the last tie group
                $tieCount = 1;
                $prevAverage = $average;
            } else {
                $tieCount++; // same average, widen the tie group
            }

            $student["position"] = $rank;
            return $student;
        });
    }

    private function calculateStudentSummary($studentResults, $studentId)
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
            "student_id" => $studentId,
            "class_name" => $student->class_name,
            "student_fullname" => $student->student_fullname,
            "results" => $combinedScores,
            "student_average" => number_format($studentAverage, 2),
            "grade" => $this->calculateGrade($studentAverage),
        ];
    }

    private function buildSubjectScores($studentResults)
    {
        return $studentResults
            ->flatMap(function ($result) {
                return $result->studentScores->map(function ($score) use ($result, ) {
                    return [
                        "subject" => $score->subject,
                        "period" => $result->period,
                        "result_type" => $result->result_type,
                        "score" => (int) $score->score,
                    ];
                });
            })
            ->groupBy(function ($score) {
                return strtolower($score["subject"]);
            })
            ->map(function ($subjectScores) {
                $subjectName = $subjectScores->first()["subject"];

                // Sum all assessments
                $assessmentScore = collect($subjectScores)
                    ->whereIn("result_type", [
                        "first_assesment",
                        "second_assesment",
                        "third_assesment",
                        "midterm",
                    ])
                    ->sum("score");

                // Sum exam (endterm)
                $examScore = collect($subjectScores)
                    ->where("result_type", "endterm")
                    ->sum("score");

                return [
                    "subject" => $subjectName,
                    "assessment_score" => $assessmentScore,
                    "exam_score" => $examScore,
                    "total_score" => $assessmentScore + $examScore,
                ];
            })
            ->values()
            ->toArray();
    }

    private function calculateGrade($average)
    {
        if ($average > 90) {
            return "EXCELLENT";
        }

        $grade = GradingSystem::where("score_to", ">=", $average)->first();
        return $grade->remark ?? "";
    }
}
