<?php

namespace App\Http\Resources\v2;

use App\Models\v2\CommunicationBookReply;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CommunicationBookReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentUserId = $request->user()->id;

        if ($currentUserId === $this->sender_id || $currentUserId === $this->receiver_id) {
            $this->updateRead($this->communication_book_id);
        }

        return [
            'communication_book' => [
                'id' => $this->communicationBook->id,
                'message' => (string)$this->communicationBook->message,
                'pinned' => (string)$this->communicationBook->pinned,
                'attachment' => (string)$this->communicationBook->file,
                'date' => Carbon::parse($this->communicationBook->created_at)->format('d M Y h:i A')
            ],
            'id' => $this->id,
            'communication_book_id' => $this->communication_book_id,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'message' => $this->message,
            'status' => $this->status,
            'sender' => [
                'id' => $this->sender->id,
                'campus' => $this->sender->campus,
                'first_name' => $this->sender->firstname,
                'last_name' => $this->sender->surname,
                'email' => $this->sender->email,
                'designation' => $this->sender->designation_id,
            ],
            'receiver' => [
                'id' => $this->receiver->id,
                'campus' => $this->receiver->campus,
                'first_name' => $this->receiver->firstname,
                'last_name' => $this->receiver->surname,
                'email' => $this->receiver->email_address,
                'designation' => (int)$this->receiver->designation_id,
            ],
            'date' => Carbon::parse($this->created_at)->format('d M Y h:i A'),
        ];
    }

    private function updateRead($id)
    {
        CommunicationBookReply::where('communication_book_id', $id)
        ->update(['status' => "read"]);
    }
}
