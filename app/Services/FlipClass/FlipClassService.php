<?php

namespace App\Services\FlipClass;

use App\Http\Resources\v2\FlipClassResource;
use App\Models\Staff;
use App\Models\v2\FlipClass;
use App\Services\ImageKit\DeleteService;
use App\Services\Upload\UploadService;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\App;

class FlipClassService
{
    use HttpResponses;

    public function addFlipClass($request)
    {
        try {
            $auth = auth();

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
        $auth = auth();
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
        $auth = auth();
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
        $auth = auth();
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
        $auth = auth();
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
        $auth = auth();
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
        $auth = auth();
        Staff::where('sch_id', $auth->sch_id)
        ->where('campus', $auth->campus)->firstOrFail();

        $flip = FlipClass::findOrFail($id);

        $flip->update([
            'status' => "inactive"
        ]);

        return $this->success(null, "Unapproved successfully");
    }
}


