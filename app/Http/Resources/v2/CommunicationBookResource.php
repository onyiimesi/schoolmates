<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CommunicationBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'sch_id' => (string)$this->sch_id,
                'campus' => (string)$this->campus,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'class_id' => (int)$this->class_id,
                'sender_id' => (int)$this->sender_id,
                'sender_type' => (string)$this->sender_type,
                'subject' => (string)$this->subject,
                'message' => (string)$this->message,
                'pinned' => (string)$this->pinned,
                'attachment' => (string)$this->file,
                'file_name' => (string)$this->file_name,
                'status' => (string)$this->status,
                'date' => $this->formatDate($this->created_at),
                'recipients' => $this->getRecipients(),
            ]
        ];
    }

    private function formatDate($date): string
    {
        return Carbon::parse($date)->format('d M Y h:i A');
    }

    private function getSenderAttributes(): array
    {
        if ($this->sender_type === "staff") {
            return [
                'campus' => $this->staff->campus,
                'first_name' => $this->staff->firstname,
                'last_name' => $this->staff->surname,
                'email' => $this->staff->email,
                'designation' => $this->staff->designation_id,
            ];
        } else {
            return [
                'campus' => $this->student->campus,
                'first_name' => $this->student->firstname,
                'last_name' => $this->student->surname,
                'email' => $this->student->email_address,
                'designation' => $this->student->designation_id,
            ];
        }
    }

    private function getReceiverAttributes($message): array
    {
        if ($message->receiver_type === "student") {
            return [
                'id' => $message->student->id,
                'campus' => $message->student->campus,
                'first_name' => $message->student->firstname,
                'last_name' => $message->student->surname,
                'email' => $message->student->email_address,
                'designation' => $message->student->designation_id,
            ];
        } else {
            return [
                'id' => $message->staff->id,
                'campus' => $message->staff->campus,
                'first_name' => $message->staff->firstname,
                'last_name' => $message->staff->surname,
                'email' => $message->staff->email,
                'designation' => $message->staff->designation_id,
            ];
        }
    }

    private function getRecipients(): array
    {
        return $this->messages->map(function ($reply) {
            return [
                'sender' => $this->getSenderAttributes(),
                'receivers' => $reply->communicationbook->messages->map(function ($message) {
                    return $this->getReceiverAttributes($message);
                })->toArray(),
            ];
        })->flatMap(function ($reply) {
            return $reply;
        })->toArray();
    }
}
