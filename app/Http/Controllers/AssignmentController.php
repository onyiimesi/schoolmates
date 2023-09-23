<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentRequest;
use App\Http\Resources\AssignmentAnswerResource;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\TheoryResource;
use App\Models\AcademicPeriod;
use App\Models\Assignment;
use App\Models\AssignmentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;

class AssignmentController extends Controller
{
    use HttpResponses;

    public function objective(Request $request)
    {

        $user = Auth::user();
        // $session = AcademicPeriod::first();

        $data = $request->json()->all();

        foreach ($data as $item) {

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

        }

        return [
            "status" => 'true',
            "message" => 'Created Successfully'
        ];

    }

    public function theory(Request $request)
    {

        $user = Auth::user();
        $data = $request->json()->all();

        foreach ($data as $item) {

            if($item['image']){
                $file = $item['image'];
                $folderName = 'https://schoolmate.powershellerp.com/public/assignment';
                $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $replace = substr($file, 0, strpos($file, ',')+1);
                $image = str_replace($replace, '', $file);

                $image = str_replace(' ', '+', $image);
                $file_name = time().'.'.$extension;
                file_put_contents(public_path().'/assignment/'.$file_name, base64_decode($image));

                $paths = $folderName.'/'.$file_name;
            }else{
                $paths = "";
            }

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
                'subject_id' => $item['subject_id'],
                'image' => $paths
            ]);

        }

        return [
            "status" => 'true',
            "message" => 'Created Successfully'
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

    public function objectiveanswer(Request $request)
    {

        $user = Auth::user();
        // $session = AcademicPeriod::first();

        if($user->designation_id === 3){
            return $this->error('', 'Unauthenticated', 401);
        }

        $data = $request->json()->all();

        foreach ($data as $item) {

            $answer = AssignmentAnswer::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'student_id' => $user->id,
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "not marked"
            ]);

        }

        return [
            "status" => 'true',
            "message" => 'Submitted Successfully'
        ];

    }

    public function theoryanswer(Request $request)
    {

        $user = Auth::user();
        // $session = AcademicPeriod::first();

        if($user->designation_id === 3){
            return $this->error('', 'Unauthenticated', 401);
        }

        $data = $request->json()->all();

        foreach ($data as $item) {

            $answer = AssignmentAnswer::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'student_id' => $user->id,
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "not marked"
            ]);

        }

        return [
            "status" => 'true',
            "message" => 'Submitted Successfully'
        ];

    }

    public function getanswer(Request $request)
    {
        $assign = AssignmentAnswer::where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->get();

        if($request->type === "objective"){
            $assigns = AssignmentAnswerResource::collection($assign);
        }else{
            $assigns = AssignmentAnswerResource::collection($assign);
        }

        return [
            "status" => 'true',
            "message" => 'List',
            "data" => $assigns
        ];
    }
}
