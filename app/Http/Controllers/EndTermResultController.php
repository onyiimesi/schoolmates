<?php

namespace App\Http\Controllers;

use App\Http\Resources\CummulativeScoreResource;
use App\Http\Resources\ResultResource;
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
                            'Average Score' => 0
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
            $displayData[] = [
                'subject' => $subject['subject'],
                'First term' => $subject['scores']['First Term'],
                'Second Term' => $subject['scores']['Second Term'],
                'Third Term' => $subject['scores']['Third Term'],
                'Total Score' => $subject['scores']['Total Score'],
                'Average Score' => $subject['scores']['Average Score']
            ];
        }

        $resourceCollection = CummulativeScoreResource::collection(collect($displayData));

        return [
            'status' => 'true',
            'message' => '',
            'data' => $resourceCollection
        ];
    }
}
