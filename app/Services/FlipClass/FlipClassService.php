<?php

namespace App\Services\FlipClass;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\FlipClassAssessmentObjResource;
use App\Http\Resources\v2\FlipClassAssessmentTheoryResource;
use App\Http\Resources\v2\FlipClassResource;
use App\Models\Staff;
use App\Models\v2\FlipClass;
use App\Models\v2\FlipClassAssessment;
use App\Services\ImageKit\DeleteService;
use App\Services\Upload\UploadService;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class FlipClassService extends Controller
{
    use HttpResponses;

    public function addFlipClass($request)
    {
        try {
            $auth = userAuth();

            $user = Staff::where('id', $request->staff_id)
            ->where('sch_id', $auth->sch_id)
            ->where('campus', $auth->campus)->first();

            if(!$user){
                return $this->error(null, 'User does not exist', 400);
            }

            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

            if($request->file) {
                $data = (new UploadService($request->file, 'flipclass', $cleanSchId))->run();
            }

            FlipClass::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'term' => $request->term,
                'session' => $request->session,
                'staff_id' => $request->staff_id,
                'week' => $request->week,
                'subject_id' => $request->subject_id,
                'class_id' => $request->class_id,
                'topic' => $request->topic,
                'description' => $request->description,
                'file' => $data->url ?? $data['url'] ?? $data,
                'file_name' => $request->file_name,
                'file_id' => $data->file_id ?? $data['file_id'] ?? null,
                'video_url' => $request->video_url,
                'submitted_by' => $user->surname . ' '. $user->firstname . ' ' . $user->middlename,
                'date_submitted' => now(),
                'status' => "inactive"
            ]);

            return $this->success(null, "Created successfully");

        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), 500);
        }
    }

    public function getFlipClass($request)
    {
        $auth = userAuth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $data = FlipClass::where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'term' => $request->term,
            'session' => $request->session,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'week' => $request->week,
        ])->get();

        $data = FlipClassResource::collection($data);
        return $this->success($data, "Lesson note");
    }

    public function getOneFlipClass($request)
    {
        $auth = userAuth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $data = FlipClass::where([
            'id' => $request->id,
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'term' => $request->term,
            'session' => $request->session,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'week' => $request->week
        ])->first();

        if(!$data){
            return $this->error(null, 'Data not found!', 404);
        }

        $data = new FlipClassResource($data);
        return $this->success($data, "Lesson note");
    }

    public function editFlipClass($request, $id)
    {
        $auth = userAuth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $flip = FlipClass::findOrFail($id);

        if($request->file){
            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
            $data = (new UploadService($request->file, 'flipclass', $cleanSchId, $flip))->run();

        }else{
            $data = [
                'url' => $flip->file,
                'file_id' => $flip->file_id
            ];
        }

        $flip->update([
            'topic' => $request->topic,
            'description' => $request->description,
            'file' => $data->url ?? $data['url'] ?? $data,
            'file_name' => $request->file_name ?? $flip->file_name,
            'file_id' => $data->file_id ?? $data['file_id'] ?? null,
        ]);

        return $this->success(null, "Updated successfully");
    }

    public function deleteFlipClass($id)
    {
        $auth = userAuth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $flip = FlipClass::findOrFail($id);
        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if(App::environment('production')){
            $fileId = $flip->file_id;
            (new DeleteService($fileId, null))->run();

        } elseif(App::environment(['staging', 'local'])){
            if ($flip->file) {
                $filename = basename($flip->file);
                $oldPath = public_path('flipclass/'. $cleanSchId .'/' . $filename);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $flip->delete();

        return $this->success(null, "Deleted successfully");
    }

    public function approveFlipClass($id)
    {
        $auth = userAuth();
        Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $flip = FlipClass::findOrFail($id);

        $flip->update([
            'status' => "active",
            'date_approved' => now(),
        ]);

        return $this->success(null, "Approved successfully");
    }

    public function unapproveFlipClass($id)
    {
        $auth = userAuth();
        Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $flip = FlipClass::findOrFail($id);

        $flip->update([
            'status' => "inactive"
        ]);

        return $this->success(null, "Unapproved successfully");
    }

    public function addObjAssessment($request)
    {
        $user = Auth::user();
        $flipClass = FlipClass::findOrFail($request->flip_class_id);

        try {

            $flipClass->assessments()->create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
                'teacher_id' => $user->id,
                'topic' => $request->topic,
                'question_type' => $request->question_type,
                'question' => $request->question,
                'question_number' => $request->question_number,
                'answer' =>  $request->answer,
                'subject_id' => $request->subject_id,
                'option1' => $request->option1,
                'option2' => $request->option2,
                'option3' => $request->option3,
                'option4' => $request->option4,
                'total_question' => $request->total_question,
                'question_mark' => $request->question_mark,
                'total_mark' => $request->total_mark,
                'week' => $request->week,
                'status' => 'unpublished',
            ]);

            return $this->success(null, 'Created Successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addTheoryAssessment($request)
    {
        $user = Auth::user();
        $flipClass = FlipClass::findOrFail($request->flip_class_id);

        try {

            $flipClass->assessments()->create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
                'teacher_id' => $user->id,
                'question_type' => $request->question_type,
                'question' => $request->question,
                'question_number' => $request->question_number,
                'answer' => $request->answer,
                'subject_id' => $request->subject_id,
                'total_question' => $request->total_question,
                'question_mark' => $request->question_mark,
                'total_mark' => $request->total_mark,
                'week' => $request->week,
                'status' => 'unpublished',
            ]);

            return $this->success(null, 'Created Successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSingleQuestion($request, $id)
    {
        $user = Auth::user();

        $assessment = FlipClassAssessment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->findOrFail($id);

        if($request->type === "objective"){
            $assessments = new FlipClassAssessmentObjResource($assessment);
        }else{
            $assessments = new FlipClassAssessmentTheoryResource($assessment);
        }

        return $this->success($assessments, 'Question detail');
    }

    public function getObjQuestions($request)
    {
        $user = Auth::user();

        $assessment = FlipClassAssessment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('flip_class_id', $request->flip_class_id)
        ->where('question_type', $request->type)
        ->where('week', $request->week)
        ->where('subject_id', $request->subject_id)
        ->get();

        if($request->type === "objective"){
            $assessments = FlipClassAssessmentObjResource::collection($assessment);
        }else{
            $assessments = FlipClassAssessmentTheoryResource::collection($assessment);
        }

        return $this->success($assessments, 'All questions');
    }

    public function editObj($request)
    {
        $assign = FlipClassAssessment::where('id', $request->id)->firstOrFail();

        $assign->update([
            'question' => $request->question,
            'question_number' => $request->question_number,
            'question_mark' => $request->question_mark,
            'answer' =>  $request->answer,
            'option1' => $request->option1,
            'option2' => $request->option2,
            'option3' => $request->option3,
            'option4' => $request->option4,
            'status' => $request->status,
        ]);

        return $this->success(null, 'Updated Successfully');
    }

    public function editThoery($request)
    {
        $assign = FlipClassAssessment::findOrFail($request->id);

        $assign->update([
            'question' => $request->question,
            'question_number' => $request->question_number,
            'question_mark' => $request->question_mark,
            'answer' => $request->answer,
            'status' => $request->status,
        ]);

        return $this->success(null, 'Updated Successfully');
    }

    public function delAssessment($id)
    {
        $ass = FlipClassAssessment::findOrFail($id);
        $ass->delete();

        return $this->success(null, 'Deleted successfully');
    }

    public function publish($request)
    {
        $user = Auth::user();
        $assign = FlipClassAssessment::where('sch_id', $user->sch_id)
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

        return $this->success(null, "Updated successfully!");
    }
}


