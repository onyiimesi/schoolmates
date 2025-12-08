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
            ->with('studentScores', 'student')
            ->get();

        $groupedResults = $sheet->groupBy('student_id');
        $signatures = Staff::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_assigned', $request->class_name)->get();

        $data = $this->getBroadsheetData($groupedResults);

        return response()->json([
            'status' => "true",
            'message' => "Broadsheet",
            'class_name' => $request->class_name,
            'data' => $data,
            'teacher' => $signatures->map(function ($teacher) {
                return [
                    "name" => $teacher->surname . ' ' . $teacher->firstname,
                    "signature" => $teacher->signature
                ];
            })->toArray()
        ], 200);
    }

    private function getBroadsheetData($groupedResults)
    {
        return $groupedResults->map(function ($studentResults, $studentId) {
            return $this->calculateStudentSummary($studentResults, $studentId);
        })->filter()->values()->toArray();
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
            'student_id' => $studentId,
            'class_name' => $student->class_name,
            'student_fullname' => $student->student_fullname,
            'results' => $combinedScores,
            'student_average' => number_format($studentAverage, 2),
            'grade' => $this->calculateGrade($studentAverage),
        ];
    }

    private function buildSubjectScores($studentResults)
    {
        return $studentResults->flatMap(function ($result) {
            return $result->studentScores->map(function ($score) use ($result) {
                return [
                    'subject' => $score->subject,
                    'period' => $result->period,
                    'score' => (int) $score->score,
                ];
            });
        })
            ->groupBy(fn($item) => $item['subject'] . '|' . $item['period']) // Dedup by subject + period
            ->map(fn($group) => $group->first()) // Take only one per subject+period
            ->groupBy('subject')
            ->map(function ($subjectScores, $subject) {
                return [
                    'subject' => $subject,
                    'total_score' => collect($subjectScores)->sum('score'),
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

        $grade = GradingSystem::where('score_to', '>=', $average)->first();
        return $grade->remark ?? "";
    }
}
