<?php

namespace App\Services\CommunicationBook;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\CommunicationBookReplyResource;
use App\Http\Resources\v2\CommunicationBookResource;
use App\Models\Staff;
use App\Models\Student;
use App\Models\v2\CommunicationBook;
use App\Models\v2\CommunicationBookReply;
use App\Services\Upload\UploadService;
use App\Traits\HttpResponses;

class CommunicationBookService extends Controller
{
    use HttpResponses;

    public function store($request)
    {
        try {

            $auth = $this->auth();
            $user = Staff::where('id', $request->staff_id)
            ->where('sch_id', $auth->sch_id)
            ->where('campus', $auth->campus)->first();

            if(!$user){
                throw new \Exception("User does not exist", 400);
            }

            if($request->attachment){
                $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
                $data = (new UploadService($request->attachment, 'communicationbook', $cleanSchId))->run();
            }

            CommunicationBook::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
                'class_id' => $request->class_id,
                'staff_id' => $request->staff_id,
                'student_id' => $request->student_id,
                'admission_number' => $request->admission_number,
                'subject' => $request->subject,
                'message' => $request->message,
                'pinned' => "1",
                'file' => $data->url ?? $data['url'] ?? $data,
                'file_id' => $data->file_id ?? $data['file_id'] ?? null,
                'status' => "active"
            ]);

            return $this->success(null, "Message sent successfully", 201);
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage());
        }
    }

    public function show()
    {
        $info = CommunicationBook::with(['staff', 'student', 'replies'])
        ->where('status', 'active')->get();
        $data = CommunicationBookResource::collection($info);

        return $this->success($data, "Detail");
    }

    public function replies($request, $id)
    {
        try {

            $data = CommunicationBook::with(['staff', 'student', 'replies'])->findOrFail($id);

            $senderType = auth()->user() instanceof Student ? Student::class : Staff::class;
            $senderId = auth()->user()->id;

            if ($senderType === Student::class) {
                $receiverType = Staff::class;
                $receiverId = $data->staff_id;
            } else {
                $receiverType = Student::class;
                $receiverId = $request->receiver_id;
            }

            $data->replies()->create([
                'communication_book_id' => $request->communication_book_id,
                'sender_id' => $senderId,
                'sender_type' => $senderType,
                'receiver_id' => $receiverId,
                'receiver_type' => $receiverType,
                'message' => $request->message,
                'status' => "unread"
            ]);

            return $this->success(null, "Reply sent successfully", 201);
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage());
        }
    }

    public function getReplies($id)
    {
        $info = CommunicationBookReply::with(['communicationBook', 'sender', 'receiver'])->where('communication_book_id', $id)->get();
        $data = CommunicationBookReplyResource::collection($info);

        return $this->success($data, "Detail");
    }

    public function close($id)
    {
        $info = CommunicationBook::findOrFail($id);

        $info->update([
            'status' => "closed"
        ]);

        return $this->success(null, "Updated successfully", 200);
    }

    public function closed()
    {
        $info = CommunicationBook::with(['staff', 'student', 'replies'])
        ->where('status', 'closed')->get();

        $data = CommunicationBookResource::collection($info);

        return $this->success($data, "Closed");
    }
}










