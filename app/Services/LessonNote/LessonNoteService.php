<?php

namespace App\Services\LessonNote;

use App\DTOs\LessonNote\CreateLessonNoteDTO;
use App\Exceptions\LessonNoteException;
use App\Http\Controllers\Controller;
use App\Http\Resources\v2\LessonNoteResource;
use App\Models\Staff;
use App\Models\v2\LessonNote;
use App\Services\ImageKit\DeleteService;
use App\Services\Upload\UploadService;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;

class LessonNoteService extends Controller
{
    use HttpResponses;

    public function addLessonNote($request, $createLessonNoteAction)
    {
        $auth = $this->auth();

        try {
            $user = Staff::where('id', $request->staff_id)
                ->where('sch_id', $auth->sch_id)
                ->where('campus', $auth->campus)
                ->first();

            if (! $user) {
                throw new LessonNoteException('User does not exist', 400);
            }

            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
            $data = (new UploadService($request->file, 'lessonnote', $cleanSchId))->run();

            $createLessonNoteAction->execute(
                new CreateLessonNoteDTO(
                    $user->sch_id,
                    $user->campus,
                    $request->term,
                    $request->session,
                    $request->staff_id,
                    $request->week,
                    $request->subject_id,
                    $request->class_id,
                    $request->topic,
                    $request->description,
                    $request->date,
                    $data['url'] ?? $data,
                    $request->file_name,
                    "{$user->surname} {$user->firstname} {$user->middlename}",
                    Date::now(),
                    "not approved",
                    $data['file_id'] ?? null,
                    $request->sub_topic,
                    $request->specific_objectives,
                    $request->previous_lesson,
                    $request->previous_knowledge,
                    $request->set_induction,
                    $request->methodology,
                    $request->teaching_aid,
                )
            );

            return $this->success(null, 'Created successfully' ,201);
        } catch (LessonNoteException $e) {
            return $e->render($request);

        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), 400);
        }
    }

    public function getLessonNote($request)
    {
        $auth = $this->auth();

        $user = Staff::where('sch_id', $auth->sch_id)
            ->where('campus', $auth->campus)
            ->firstOrFail();

        $data = LessonNote::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'term' => $request->term,
                'session' => $request->session,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'week' => $request->week,
            ])
            ->get();

        return $this->success(LessonNoteResource::collection($data), "Lesson note");
    }

    public function getOneLessonNote($request)
    {
        $auth = $this->auth();

        $user = Staff::where('sch_id', $auth->sch_id)
            ->where('campus', $auth->campus)
            ->firstOrFail();

        $data = LessonNote::where([
                'id' => $request->lesson_id,
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'term' => $request->term,
                'session' => $request->session,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'week' => $request->week
            ])
            ->first();

        if (! $data) {
            return $this->error(null, 'Data not found!', 404);
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
            'topic' => $request->topic ?? $lesson->topic,
            'description' => $request->description ?? $lesson->description,
            'file' => $data->url ?? $data['url'] ?? $data,
            'file_name' => $request->file_name ?? $lesson->file_name,
            'file_id' => $data->file_id ?? $data['file_id'] ?? null,
            'date' => $request->date ?? $lesson->date,
            'sub_topic' => $request->sub_topic ?? $lesson->sub_topic,
            'specific_objectives' => $request->specific_objectives ?? $lesson->specific_objectives,
            'previous_lesson' => $request->previous_lesson ?? $lesson->previous_lesson,
            'previous_knowledge' => $request->previous_knowledge ?? $lesson->previous_knowledge,
            'set_induction' => $request->set_induction ?? $lesson->set_induction,
            'methodology' => $request->methodology ?? $lesson->methodology,
            'teaching_aid' => $request->teaching_aid ?? $lesson->teaching_aid,
        ]);

        return $this->success(null, "Updated successfully");
    }

    public function deleteLessonNote($id)
    {
        $auth = $this->auth();
        $user = Staff::where('sch_id', $auth->sch_id)
            ->where('campus', $auth->campus)
            ->first();

        if(! $user){
            throw new LessonNoteException("User does not exist", 404);
        }

        $lesson = LessonNote::findOrFail($id);
        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if(app()->environment('production')){
            $fileId = $lesson->file_id;
            (new DeleteService($fileId, null))->run();

        } else {
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
            ->where('campus', $auth->campus)
            ->first();

        if(!$user){
            throw new LessonNoteException("User does not exist", 404);
        }

        $lesson = LessonNote::find($id);

        if(! $lesson){
            throw new LessonNoteException("Lesson note does not exist", 404);
        }

        $lesson->update([
            'status' => "approved",
            'date_approved' => Date::now(),
        ]);

        return $this->success(null, "Approved successfully");
    }

    public function unapproveLessonNote($id)
    {
        $auth = $this->auth();
        $user = Staff::where('sch_id', $auth->sch_id)
            ->where('campus', $auth->campus)
            ->first();

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


