<?php

namespace App\Http\Controllers;

use App\Models\GradingSystem;
use App\Models\Staff;
use App\Models\Result;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadSheetController extends Controller
{
    use HttpResponses;

    public function broadsheet(Request $request)
    {
        $user = Auth::user();

        $sheet = Result::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $request->class_name)
            ->where('term', $request->term)
            ->whereIn('period', ['First Half', 'Second Half'])
            ->where('session', $request->session)
            ->with('studentscore', 'student')
            ->get();

        $groupedResults = $sheet->groupBy('student_id');
        $signature = Staff::where('class_assigned', $request->class_name)->get();

        $data = $groupedResults->map(function ($studentResults, $studentId) {
            $firstEntry = $studentResults->first();

            $totalScore = 0;
            $subjectScores = [];

            // Build subject-wise scores
            foreach ($studentResults as $result) {
                foreach ($result->studentscore as $score) {
                    $subject = $score->subject;
                    $scoreValue = (int) $score->score;

                    if (!isset($subjectScores[$subject])) {
                        $subjectScores[$subject] = 0;
                    }

                    $subjectScores[$subject] += $scoreValue;
                    $totalScore += $scoreValue;
                }
            }

            $totalSubjects = count($subjectScores);
            $studentAverage = $totalSubjects > 0 ? $totalScore / $totalSubjects : 0;

            // Grade logic
            if ($studentAverage > 90) {
                $grades = "EXCELLENT";
            } else {
                $grade = GradingSystem::where('score_to', '>=', $studentAverage)->first();
                $grades = $grade->remark ?? "";
            }

            // Format subject results
            $combinedScores = collect($subjectScores)->map(function ($totalScore, $subject) {
                return [
                    'subject' => $subject,
                    'total_score' => $totalScore,
                ];
            })->values()->toArray();

            // Optionally skip students with all zero scores
            $hasNonZero = collect($subjectScores)->some(fn($score) => $score > 0);
            if (!$hasNonZero) {
                return null;
            }

            return [
                'student_id' => $studentId,
                'class_name' => $firstEntry->class_name,
                'student_fullname' => $firstEntry->student_fullname,
                'results' => $combinedScores,
                'student_average' => number_format($studentAverage, 2),
                'grade' => $grades,
            ];
        })->filter()->values()->toArray(); // filter out nulls

        return response()->json([
            'status' => "true",
            'message' => "Broadsheet",
            'class_name' => $request->class_name,
            'data' => $data,
            'teacher' => $signature->map(function ($teacher) {
                return [
                    "name" => $teacher->surname . ' ' . $teacher->firstname,
                    "signature" => $teacher->signature
                ];
            })->toArray()
        ], 200);
    }
}
