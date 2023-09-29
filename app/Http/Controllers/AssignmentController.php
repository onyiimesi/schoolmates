<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssignmentAnswerResource;
use App\Http\Resources\AssignmentMarkResource;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\TheoryResource;
use App\Models\AcademicPeriod;
use App\Models\Assignment;
use App\Models\AssignmentAnswer;
use App\Models\AssignmentMark;
use App\Models\AssignmentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;

class AssignmentController extends Controller
{
    use HttpResponses;

    public function objective(Request $request)
    {

        $user = Auth::user();

        $data = $request->json()->all();

        foreach ($data as $item) {

            Assignment::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'teacher_id' => $user->id,
                'question_type' => $item['question_type'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'answer' =>  $item['answer'],
                'subject_id' => $item['subject_id'],
                'option1' => $item['option1'],
                'option2' => $item['option2'],
                'option3' => $item['option3'],
                'option4' => $item['option4'],
                'total_question' => $item['total_question'],
                'question_mark' => $item['question_mark'],
                'total_mark' => $item['total_mark'],
                'week' => $item['week']
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

            Assignment::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'teacher_id' => $user->id,
                'question_type' => $item['question_type'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'answer' => $item['answer'],
                'subject_id' => $item['subject_id'],
                'image' => $paths,
                'total_question' => $item['total_question'],
                'question_mark' => $item['question_mark'],
                'total_mark' => $item['total_mark'],
                'week' => $item['week']
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
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "not marked",
                'submitted' =>  $item['submitted']
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
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "not marked",
                'submitted' =>  $item['submitted']
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

    public function objectivemark(Request $request)
    {

        $user = Auth::user();

        $data = $request->json()->all();


        foreach ($data as $item) {

            $assignment = AssignmentMark::where('question_id', $item['question_id'])->first();

            if($assignment == ""){

                AssignmentMark::create([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $item['period'],
                    'term' => $item['term'],
                    'session' => $item['session'],
                    'student_id' => $item['student_id'],
                    'subject_id' => $item['subject_id'],
                    'question_id' => $item['question_id'],
                    'question' => $item['question'],
                    'question_number' => $item['question_number'],
                    'question_type' => $item['question_type'],
                    'answer' =>  $item['answer'],
                    'correct_answer' =>  $item['correct_answer'],
                    'mark' => "marked",
                    'submitted' =>  $item['submitted'],
                    'teacher_mark' =>  $item['teacher_mark']
                ]);

            }else{

                $assignment->update([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $item['period'],
                    'term' => $item['term'],
                    'session' => $item['session'],
                    'student_id' => $item['student_id'],
                    'subject_id' => $item['subject_id'],
                    'question_id' => $item['question_id'],
                    'question' => $item['question'],
                    'question_number' => $item['question_number'],
                    'question_type' => $item['question_type'],
                    'answer' =>  $item['answer'],
                    'correct_answer' =>  $item['correct_answer'],
                    'mark' => "marked",
                    'submitted' =>  $item['submitted'],
                    'teacher_mark' =>  $item['teacher_mark']
                ]);

            }


        }

        return [
            "status" => 'true',
            "message" => 'Submitted Successfully'
        ];

    }

    public function theorymark(Request $request)
    {

        $user = Auth::user();

        $data = $request->json()->all();


        foreach ($data as $item) {

            $assignment = AssignmentMark::where('question_id', $item['question_id'])->first();

            if($assignment == ""){

                AssignmentMark::create([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $item['period'],
                    'term' => $item['term'],
                    'session' => $item['session'],
                    'student_id' => $item['student_id'],
                    'subject_id' => $item['subject_id'],
                    'question_id' => $item['question_id'],
                    'question' => $item['question'],
                    'question_number' => $item['question_number'],
                    'question_type' => $item['question_type'],
                    'answer' =>  $item['answer'],
                    'correct_answer' =>  $item['correct_answer'],
                    'mark' => "marked",
                    'submitted' =>  $item['submitted'],
                    'teacher_mark' =>  $item['teacher_mark']
                ]);

            }else{

                $assignment->update([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $item['period'],
                    'term' => $item['term'],
                    'session' => $item['session'],
                    'student_id' => $item['student_id'],
                    'subject_id' => $item['subject_id'],
                    'question_id' => $item['question_id'],
                    'question' => $item['question'],
                    'question_number' => $item['question_number'],
                    'question_type' => $item['question_type'],
                    'answer' =>  $item['answer'],
                    'correct_answer' =>  $item['correct_answer'],
                    'mark' => "marked",
                    'submitted' =>  $item['submitted'],
                    'teacher_mark' =>  $item['teacher_mark']
                ]);

            }


        }

        return [
            "status" => 'true',
            "message" => 'Submitted Successfully'
        ];

    }

    public function marked(Request $request)
    {
        $assign = AssignmentMark::where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->get();

        if($request->type === "objective"){
            $assigns = AssignmentMarkResource::collection($assign);
        }else{
            $assigns = AssignmentMarkResource::collection($assign);
        }

        return [
            "status" => 'true',
            "message" => 'List',
            "data" => $assigns
        ];
    }

    public function markedbystudent(Request $request)
    {
        $assign = AssignmentMark::where('student_id', $request->student_id)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->get();

        if($request->type === "objective"){
            $assigns = AssignmentMarkResource::collection($assign);
        }else{
            $assigns = AssignmentMarkResource::collection($assign);
        }

        return [
            "status" => 'true',
            "message" => 'List',
            "data" => $assigns
        ];
    }

    public function editObjAssign(Request $request)
    {
        $assign = Assignment::where('id', $request->id)->first();

        if(!$assign){
            return $this->error('', 'Assignment does not exist', 400);
        }

        $assign->update([
            'question' => $request->question,
            'answer' =>  $request->answer,
            'option1' => $request->option1,
            'option2' => $request->option2,
            'option3' => $request->option3,
            'option4' => $request->option4
        ]);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully'
        ];
    }

    public function editTheoAssign(Request $request)
    {
        $assign = Assignment::where('id', $request->id)->first();

        if(!$assign){
            return $this->error('', 'Assignment does not exist', 400);
        }

        $assign->update([
            'question' => $request->question,
            'answer' => $request->answer
        ]);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully'
        ];
    }

    public function delAssign(Request $request)
    {
        $ass = Assignment::where('id', $request->id)->first();

        if(!$ass){
            return $this->error('', 'Assignment does not exist', 400);
        }

        $ass->delete();

        return response(null, 204);
    }

    public function result (Request $request)
    {
        $user = Auth::user();

        AssignmentResult::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->session,
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'question_type' => $request->question_type,
            'question_number' => $request->question_number,
            'mark' => $request->mark,
            'total_mark' => $request->total_mark,
            'score' => $request->score
        ]);

        return [
            "status" => 'true',
            "message" => 'Submitted Successfully'
        ];
    }
}
