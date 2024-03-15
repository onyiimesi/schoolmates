<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssignmentAnswerResource;
use App\Http\Resources\AssignmentMarkResource;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\AssignmentResultResource;
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

    const SUCCESS = 'Submitted Successfully';
    const ASSIGNMENT_ERROR = 'Assignment does not exist';

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
                $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

                $file = $item['image'];
                $baseFolder = 'assignment';
                $userFolder = $cleanSchId;
                $folderPath = public_path($baseFolder . '/' . $userFolder);

                $folderName = env('ASSIGNMENT_FOLDER') . '/' . $cleanSchId;
                $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $replace = substr($file, 0, strpos($file, ',')+1);
                $image = str_replace($replace, '', $file);

                $image = str_replace(' ', '+', $image);
                $file_name = time().'.'.$extension;

                if (!file_exists(public_path($baseFolder))) {
                    mkdir(public_path($baseFolder), 0777, true);
                }

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }

                file_put_contents($folderPath.'/'.$file_name, base64_decode($image));

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
        $user = Auth::user();

        $assign = Assignment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->where('status', 'published')
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

    public function assignUnpublished(Request $request)
    {
        $user = Auth::user();

        $assign = Assignment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->where('status', 'unpublished')
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

        if($user->designation_id === 3){
            return $this->error('', 'Unauthenticated', 401);
        }

        $data = $request->json()->all();

        foreach ($data as $item) {

            AssignmentAnswer::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $user->id,
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "not marked",
                'submitted' =>  $item['submitted'],
                'week' => $item['week']
            ]);

        }

        return [
            "status" => 'true',
            "message" => SELF::SUCCESS
        ];

    }

    public function theoryanswer(Request $request)
    {

        $user = Auth::user();

        if($user->designation_id === 3){
            return $this->error('', 'Unauthenticated', 401);
        }

        $data = $request->json()->all();

        foreach ($data as $item) {

            AssignmentAnswer::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $user->id,
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "not marked",
                'submitted' =>  $item['submitted'],
                'week' => $item['week']
            ]);

        }

        return $this->success(null, SELF::SUCCESS, 200);

    }

    public function getanswer(Request $request)
    {
        $user = Auth::user();

        $assign = AssignmentAnswer::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->get();

        if($request->type === "objective"){
            $assigns = AssignmentAnswerResource::collection($assign);
        }elseif($request->type === "theory"){
            $assigns = AssignmentAnswerResource::collection($assign);
        }else{
            $assigns = [];
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

            AssignmentMark::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $item['student_id'],
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "marked",
                'submitted' =>  $item['submitted'],
                'teacher_mark' =>  $item['teacher_mark'],
                'week' => $item['week']
            ]);

        }

        return [
            "status" => 'true',
            "message" => SELF::SUCCESS
        ];

    }

    public function theorymark(Request $request)
    {

        $user = Auth::user();
        $data = $request->json()->all();

        foreach ($data as $item) {

            AssignmentMark::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $item['student_id'],
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "marked",
                'submitted' =>  $item['submitted'],
                'teacher_mark' =>  $item['teacher_mark'],
                'week' => $item['week']
            ]);

        }

        return $this->success(null, SELF::SUCCESS, 200);

    }

    public function marked(Request $request)
    {
        $user = Auth::user();

        $assign = AssignmentMark::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->get();

        if($request->type === "objective"){
            $assigns = AssignmentMarkResource::collection($assign);
        }elseif($request->type === "theory"){
            $assigns = AssignmentMarkResource::collection($assign);
        }else{
            $assigns = [];
        }

        return [
            "status" => 'true',
            "message" => 'List',
            "data" => $assigns
        ];
    }

    public function markedbystudent(Request $request)
    {
        $user = Auth::user();

        $assign = AssignmentMark::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('student_id', $request->student_id)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->get();

        if($request->type === "objective"){
            $assigns = AssignmentMarkResource::collection($assign);
        }elseif($request->type === "theory"){
            $assigns = AssignmentMarkResource::collection($assign);
        }else{
            $assigns = [];
        }

        return [
            "status" => 'true',
            "message" => 'List',
            "data" => $assigns
        ];
    }

    public function editObjAssign(Request $request)
    {
        $data = $request->json()->all();

        foreach ($data as $item) {

            $assign = Assignment::where('id', $item['id'])->first();

            if(!$assign){
                return $this->error('', SELF::ASSIGNMENT_ERROR, 400);
            }

            $assign->update([
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_mark' => $item['question_mark'],
                'answer' =>  $item['answer'],
                'option1' => $item['option1'],
                'option2' => $item['option2'],
                'option3' => $item['option3'],
                'option4' => $item['option4']
            ]);
        }

        return [
            "status" => 'true',
            "message" => 'Updated Successfully'
        ];
    }

    public function editTheoAssign(Request $request)
    {
        $assign = Assignment::where('id', $request->id)->first();

        if(!$assign){
            return $this->error('', SELF::ASSIGNMENT_ERROR, 400);
        }

        $assign->update([
            'question' => $request->question,
            'question_number' => $request->question_number,
            'question_mark' => $request->question_mark,
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
            return $this->error('', SELF::ASSIGNMENT_ERROR, 400);
        }

        $ass->delete();

        return response(null, 204);
    }

    public function result (Request $request)
    {
        $user = Auth::user();
        $data = $request->json()->all();

        foreach($data as $item){
            
            $item->validate([
                'period' => 'required',
                'term' => 'required',
                'assignment_id' => 'required',
                'student_id' => 'required',
                'subject_id' => 'required',
                'question_type' => 'required',
                'student_mark' => 'required',
                'total_mark' => 'required',
                'score' => 'required',
                'week' => 'required'
            ]);

            AssignmentResult::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $item['student_id'],
                'subject_id' => $item['subject_id'],
                'question_type' => $item['question_type'],
                'student_mark' => $item['student_mark'],
                'total_mark' => $item['total_mark'],
                'score' => $item['score'],
                'week' => $item['week']
            ]);
        }

        return [
            "status" => 'true',
            "message" => SELF::SUCCESS
        ];
    }

    public function resultassign(Request $request)
    {
        $user = Auth::user();

        $assign = AssignmentResult::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->get();

        $assigns = AssignmentResultResource::collection($assign);

        return [
            "status" => 'true',
            "message" => 'List',
            "data" => $assigns
        ];
    }

    public function resultassignstu(Request $request)
    {
        $user = Auth::user();

        $assign = AssignmentResult::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('student_id', $request->student_id)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->type)
        ->get();

        $assigns = AssignmentResultResource::collection($assign);

        return [
            "status" => 'true',
            "message" => 'List',
            "data" => $assigns
        ];
    }

    public function publish(Request $request)
    {
        $request->validate([
            'period' => ['required', 'string'],
            'term' => ['required', 'string'],
            'session' => ['required', 'string'],
            'question_type' => ['required', 'string'],
            'week' => ['required', 'string'],
            'is_publish' => ['required', 'numeric', 'in:0,1']
        ]);

        $user = Auth::user();
        $assign = Assignment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->question_type)
        ->where('week', $request->week)
        ->get();

        if ($request->is_publish == 1) {
            $assign->each(function ($assignment) {
                $assignment->update([
                    'status' => 'published'
                ]);
            });
        } else {
            $assign->each(function ($assignment) {
                $assignment->update([
                    'status' => 'unpublished'
                ]);
            });
        }

        return $this->success(null, "Updated successfully!", 200);
    }
}
