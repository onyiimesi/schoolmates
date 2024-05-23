<?php

namespace App\Services\LessonNote;

use App\Exceptions\LessonNoteException;
use App\Http\Controllers\Controller;
use App\Http\Resources\v2\LessonNoteResource;
use App\Models\Staff;
use App\Models\v2\LessonNote;
use App\Services\ImageKit\DeleteService;
use App\Services\Upload\UploadService;
use App\Traits\HttpResponses;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use ImageKit\ImageKit;

class LessonNoteService extends Controller
{
    use HttpResponses;

    public function addLessonNote($request)
    {
        try {
            $auth = $this->auth();
            $user = Staff::where('id', $request->staff_id)
            ->where('sch_id', $auth->sch_id)
            ->where('campus', $auth->campus)->first();

            if(!$user){
                throw new LessonNoteException("User does not exist", 400);
            }

            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

            $data = (new UploadService($request->file, 'lessonnote', $cleanSchId))->run();

            LessonNote::create([
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
                'submitted_by' => $user->surname . ' '. $user->firstname . ' ' . $user->middlename,
                'date_submitted' => Carbon::now(),
                'status' => "not approved"
            ]);

            return $this->success(null, "Created successfully");

        } catch (LessonNoteException $e) {
            return $e->render($request);

        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), 500);
        }
    }

    public function getLessonNote($request)
    {
        $auth = $this->auth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $data = LessonNote::where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'term' => $request->term,
            'session' => $request->session,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'week' => $request->week,
        ])->get();

        $data = LessonNoteResource::collection($data);
        return $this->success($data, "Lesson note");
    }

    public function getOneLessonNote($request)
    {
        $auth = $this->auth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $data = LessonNote::where([
            'id' => $request->lesson_id,
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'term' => $request->term,
            'session' => $request->session,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'week' => $request->week
        ])->first();

        if(!$data){
            throw new LessonNoteException("Data not found!", 404);
        }

        $data = new LessonNoteResource($data);
        return $this->success($data, "Lesson note");
    }

    public function editLessonNote($request, $id)
    {
        $auth = $this->auth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $lesson = LessonNote::findOrFail($id);

        if($request->file){
            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
            $data = (new UploadService($request->file, 'lessonnote', $cleanSchId, $lesson))->run();

        }else{
            $data = [
                'url' => $lesson->file,
                'file_id' => $lesson->file_id
            ];
        }

        $lesson->update([
            'topic' => $request->topic,
            'description' => $request->description,
            'file' => $data->url ?? $data['url'] ?? $data,
            'file_name' => $request->file_name,
            'file_id' => $data->file_id ?? $data['file_id'] ?? null,
        ]);

        return $this->success(null, "Updated successfully");
    }

    public function deleteLessonNote($id)
    {
        $auth = $this->auth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $lesson = LessonNote::findOrFail($id);
        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if(App::environment('production')){
            $fileId = $lesson->file_id;
            (new DeleteService($fileId, null))->run();

        } elseif(App::environment(['staging', 'local'])){
            if ($lesson->file) {
                $filename = basename($lesson->file);
                $oldPath = public_path('lessonnote/'. $cleanSchId .'/' . $filename);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $lesson->delete();

        return $this->success(null, "Deleted successfully");
    }

    public function approveLessonNote($id)
    {
        $auth = $this->auth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->first();

        if(!$user){
            throw new LessonNoteException("User does not exist", 404);
        }

        $lesson = LessonNote::find($id);

        if(!$lesson){
            throw new LessonNoteException("Lesson note does not exist", 404);
        }

        $lesson->update([
            'status' => "approved",
            'date_approved' => Carbon::now(),
        ]);

        return $this->success(null, "Approved successfully");
    }

    public function unapproveLessonNote($id)
    {
        $auth = $this->auth();
        $user = Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->first();

        if(!$user){
            throw new LessonNoteException("User does not exist", 404);
        }

        $lesson = LessonNote::find($id);

        if(!$lesson){
            throw new LessonNoteException("Lesson note does not exist", 404);
        }

        $lesson->update([
            'status' => "not approved"
        ]);

        return $this->success(null, "Unapproved successfully");
    }
}


