<?php

namespace App\Http\Controllers;

use App\Http\Resources\CummulativeScoreResource;
use App\Http\Resources\ResultResource;
use App\Models\GradingSystem;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndTermResultController extends Controller
{
    public function endterm(Request $request){

        $user = Auth::user();

        $search = Result::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("student_id", $request->student_id)
        ->where("period", 'Second Half')
        ->where("term", $request->term)
        ->where("session", $request->session)->get();

        $s = ResultResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }

    public function cummulative(Request $request)
    {
        $user = Auth::user();

        $results = Result::where('student_id', $request->student_id)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->with('studentscore')
        ->get();

        $subjects = [];

        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                $subject = $score->subject;
                $term = $result->term;
                $scoreValue = $score->score;

                if (!isset($subjects[$subject])) {
                    $subjects[$subject] = [
                        'subject' => $subject,
                        'scores' => [
                            'First Term' => [],
                            'Second Term' => [],
                            'Third Term' => [],
                            'Total Score' => 0,
                            'Average Score' => 0,
                            'Remark' => ""
                        ]
                    ];
                }

                $subjects[$subject]['scores'][$term][] = $scoreValue;
                $subjects[$subject]['scores']['Total Score'] += $scoreValue;
            }
        }

        foreach ($subjects as &$subject) {
            foreach (['First Term', 'Second Term', 'Third Term'] as $term) {
                $subject['scores'][$term] = array_sum($subject['scores'][$term]);
            }

            $subject['scores']['Average Score'] = $subject['scores']['Total Score'] / 3;
        }

        $displayData = [];

        foreach ($subjects as $subject) {

            $scoreToCheck = $subject['scores']['Total Score'];
            $grades = GradingSystem::where('score_to', '>=', $scoreToCheck)->get();
            $remark = null;

            if ($grades->isNotEmpty()) {
                $remark = $grades->first()->remark;
            }

            $displayData[] = [
                'subject' => $subject['subject'],
                'First term' => $subject['scores']['First Term'],
                'Second Term' => $subject['scores']['Second Term'],
                'Third Term' => $subject['scores']['Third Term'],
                'Total Score' => $subject['scores']['Total Score'],
                'Average Score' => $subject['scores']['Average Score'],
                'Remark' => $remark
            ];
        }

        $resourceCollection = CummulativeScoreResource::collection(collect($displayData));

        return [
            'status' => 'true',
            'message' => '',
            'data' => $resourceCollection
        ];
    }

    public function average(Request $request)
    {
        $results = Result::where('class_name', $request->class_name)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->with('studentscore')
        ->get();

        $count = Result::where('class_name', $request->class_name)->count();

        $totalScore = 0;

        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                $totalScore += $score->score;
            }
        }

        $classAverage = $totalScore > 0 ? $totalScore / $count : 0;

        return [
            "status" => "true",
            "message" => "Total Average",
            "data" => $classAverage
        ];
    }

    public function endaverage(Request $request)
    {
        $results = Result::where('class_name', $request->class_name)
        ->where('session', $request->session)
        ->with('studentscore')
        ->get();

        $count = Result::where('class_name', $request->class_name)->count();

        $totalScore = 0;

        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                $totalScore += $score->score;
            }
        }

        $classAverage = $totalScore > 0 ? $totalScore / $count : 0;

        return [
            "status" => "true",
            "message" => "Total Average",
            "data" => $classAverage
        ];
    }

    public function studentaverage(Request $request)
    {
        $results = Result::where('student_id', $request->student_id)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->with('studentscore')
        ->get();

        $totalScore = 0;
        $totalSubject = 0;

        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                $totalScore += $score->score;
                $totalSubject++;
            }
        }

        $studentAverage = $totalSubject > 0 ? $totalScore / $totalSubject : 0;

        return [
            "status" => "true",
            "message" => "Total Average",
            "data" => $studentAverage
        ];
    }
}
