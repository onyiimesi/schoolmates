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

        $groupedResults = $sheet->groupBy(['student_id']);
        $signature = Staff::where('class_assigned', $request->class_name)->get();

        $data = $groupedResults->map(function ($studentResults, $studentId) {
            $name = $studentResults->first();

            $totalScore = 0;
            $uniqueSubjects = [];
            foreach ($studentResults as $result) {
                foreach ($result->studentscore as $score) {
                    if ($score->score != 0) {
                        $totalScore += $score->score;
                        $uniqueSubjects[] = $score->subject;
                    }
                }
            }
            $totalSubject = count(array_unique($uniqueSubjects));
            $studentAverage = $totalSubject > 0 ? $totalScore / $totalSubject : 0;

            $grade = GradingSystem::where('score_to', '>=', $studentAverage)->first();
            if($studentAverage > 90){
                $grades = "EXCELLENT";
            }else{
                $grades = $grade->remark ?? "";
            }

            $combinedScores = $studentResults->flatMap(function ($result) {
                return $result->studentscore->map(function ($score) {
                    return [
                        'subject' => $score->subject,
                        'total_score' => $score->score,
                    ];
                });
            })->groupBy('subject')->map(function ($subjectScores, $subject) {
                $average = round($subjectScores->avg('score'));
                return [
                    'subject' => $subject,
                    'total_score' => $average,
                ];
            })->values()->toArray();

            return [
                'student_id' => $studentId,
                'class_name' => $name->class_name,
                'student_fullname' => $name->student_fullname,
                'results' => $combinedScores,
                'student_average' => number_format($studentAverage, 2),
                'grade' => $grades
            ];
        })->values()->toArray();

        if($data){
            return response()->json([
                'status' => "true",
                'message' => "Broadsheet",
                'class_name' => $request->class_name,
                'data' => $data,
                'teacher' => $signature->map(function($teacher) {
                    return [
                        "name" => $teacher->surname .' '. $teacher->firstname,
                        "signature" => $teacher->signature
                    ];
                })->toArray()
            ], 200);
        }

        return $this->success([], "Broadsheet", 200);
    }
}
