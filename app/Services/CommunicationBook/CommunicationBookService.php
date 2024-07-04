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
use Illuminate\Support\Facades\DB;

class CommunicationBookService extends Controller
{
    use HttpResponses;

    public function store($request)
    {
        try {

            DB::transaction(function () use ($request) {
                $auth = $this->auth();

                $user = Staff::where('id', $auth->id)
                ->where('sch_id', $auth->sch_id)
                ->where('campus', $auth->campus)->first();

                if (!$user) {
                    $user = Student::where('id', $auth->id)
                        ->where('sch_id', $auth->sch_id)
                        ->where('campus', $auth->campus)
                        ->first();
                }

                if (!$user) {
                    throw new \Exception("User does not exist", 400);
                }

                $dataFile = null;

                if (!empty($request->file)) {
                    $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
                    $dataFile = (new UploadService($request->file, 'communicationbook', $cleanSchId))->run();
                }

                $book = CommunicationBook::create([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'class_id' => $request->class_id,
                    'sender_id' => $request->sender_id,
                    'sender_type' => $request->sender_type,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'pinned' => "1",
                    'file' => $dataFile->url ?? $dataFile['url'] ?? $dataFile,
                    'file_name' => $request->file_name,
                    'file_id' => $dataFile->file_id ?? $dataFile['file_id'] ?? null,
                    'status' => "active"
                ]);

                foreach ($request->recipients as $messageData) {
                    $book->messages()->create([
                        'receiver_id' => $messageData['recipient_id'],
                        'receiver_type' => $messageData['receiver_type']
                    ]);
                }
            });

            return $this->success(null, "Message sent successfully", 201);
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage());
        }
    }

    public function show($classId)
    {
        $user = $this->auth();

        $info = CommunicationBook::with(['staff', 'student', 'replies', 'messages'])
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
                $receiverId = $data->sender_id;
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
        $info = CommunicationBookReply::with(['communicationBook', 'sender', 'receiver'])
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

        return $this->success(null, "Updated successfully");
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

    public function edit($request, $id)
    {
        $info = CommunicationBook::findOrFail($id);
        $user = $this->auth();

        try {
            if (!empty($request->file)) {
                $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
                $dataFile = (new UploadService($request->file, 'communicationbook', $cleanSchId))->run();
            }else {
                $dataFile = [
                    'url' => $info->file,
                    'file_id' => $info->file_id
                ];
            }

            $info->update([
                'subject' => $request->subject,
                'message' => $request->message,
                'pinned' => "1",
                'file' => $dataFile->url ?? $dataFile['url'] ?? $dataFile,
                'file_name' => $request->file_name,
                'file_id' => $dataFile->file_id ?? $dataFile['file_id'] ?? null,
                'status' => "active"
            ]);

            return $this->success(null, "Updated successfully");
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage());
        }
    }

    public function editReply($request, $id)
    {
        $info = CommunicationBookReply::findOrFail($id);

        $info->update([
            'message' => $request->message
        ]);

        return $this->success(null, "Updated successfully");
    }

    public function deleteReply($id)
    {
        $info = CommunicationBookReply::findOrFail($id);
        $info->delete();

        return $this->success(null, "Deleted successfully");
    }

    public function unreadCount()
    {
        $auth = $this->auth();

        $info = CommunicationBookReply::where('receiver_id', $auth->id)
        ->where('status', 'unread')
        ->count();

        return $this->success($info, "Unread message count");
    }
}










