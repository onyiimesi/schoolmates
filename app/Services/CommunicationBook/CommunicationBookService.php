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

            $data = $request->json()->all();

            foreach ($data as $item) {

                $auth = $this->auth();
                $user = Staff::where('id', $item['staff_id'])
                ->where('sch_id', $auth->sch_id)
                ->where('campus', $auth->campus)->first();

                if(!$user){
                    throw new \Exception("User does not exist", 400);
                }

                $dataFile = null;

                if($item['attachment']){
                    $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
                    $dataFile = (new UploadService($item['attachment'], 'communicationbook', $cleanSchId))->run();
                }

                CommunicationBook::create([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $item['period'],
                    'term' => $item['term'],
                    'session' => $item['session'],
                    'class_id' => $item['class_id'],
                    'staff_id' => $item['staff_id'],
                    'student_id' => $item['student_id'],
                    'admission_number' => $item['admission_number'],
                    'subject' => $item['subject'],
                    'message' => $item['message'],
                    'pinned' => "1",
                    'file' => $dataFile->url ?? $dataFile['url'] ?? $dataFile,
                    'file_id' => $dataFile->file_id ?? $dataFile['file_id'] ?? null,
                    'status' => "active"
                ]);
            }

            return $this->success(null, "Message sent successfully", 201);
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage());
        }
    }

    public function show($classId)
    {
        $user = $this->auth();

        $info = CommunicationBook::with(['staff', 'student', 'replies'])
        ->where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('class_id', $classId)
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
        $user = $this->auth();

        $info = CommunicationBookReply::with(['communicationBook', 'sender', 'receiver'])
        ->where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('communication_book_id', $id)
        ->get();
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

    public function closed($classId)
    {
        $user = $this->auth();
        
        $info = CommunicationBook::with(['staff', 'student', 'replies'])
        ->where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('class_id', $classId)
        ->where('status', 'closed')
        ->get();

        $data = CommunicationBookResource::collection($info);

        return $this->success($data, "Closed");
    }
}










