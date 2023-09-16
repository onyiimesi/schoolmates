<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\TheoryResource;
use App\Models\AcademicPeriod;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function create(Request $request)
    {

        $user = Auth::user();
        // $session = AcademicPeriod::first();

        $data = $request->json()->all();

        foreach ($data as $item) {

            if ($item['question_type'] == 'objective') {

                $assignment = Assignment::create([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $item['period'],
                    'term' => $item['term'],
                    'session' => $item['session'],
                    'teacher_id' => $user->id,
                    'question_type' => $item['question_type'],
                    'question' => $item['question'],
                    'answer' =>  $item['answer'],
                    'subject_id' => $item['subject_id'],
                    'option1' => $item['option1'],
                    'option2' => $item['option2'],
                    'option3' => $item['option3'],
                    'option4' => $item['option4']
                ]);

            } elseif ($item['question_type'] == 'theory') {

                $assignment = Assignment::create([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $item['period'],
                    'term' => $item['term'],
                    'session' => $item['session'],
                    'teacher_id' => $user->id,
                    'question_type' => $item['question_type'],
                    'question' => $item['question'],
                    'answer' => $item['answer'],
                    'subject_id' => $item['subject_id']
                ]);

            }

        }

        return [
            "status" => 'true',
            "message" => 'Created Successfully',
            "data" => $assignment
        ];

    }

    public function assign(Request $request)
    {
        $assign = Assignment::where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->get();

        if($request->type === "objective"){
            $assigns = AssignmentResource::collection($assign);
        }else{
            $assigns = TheoryResource::collection($assign);
        }

        return [
            "status" => 'true',
            "message" => 'List',
            "data" => $assigns
        ];
    }
}
