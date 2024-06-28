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
                'status' => (string)$this->status,
                'date' => Carbon::parse($this->created_at)->format('d M Y h:i A'),
                'recipients' => $this->messages->map(function ($reply) {
                    return [
                        'sender' => [
                            'id' => (int)$this->sender_id,
                            'campus' => $this->sender_type === "staff" ? $this->staff->campus : $this->student->campus,
                            'first_name' => $this->sender_type === "staff" ? $this->staff->firstname : $this->student->firstname,
                            'last_name' => $this->sender_type === "staff" ? $this->staff->surname : $this->student->surname,
                            'email' => $this->sender_type === "staff" ? $this->staff->email : $this->student->email_address,
                            'designation' => $this->sender_type === "staff" ? $this->staff->designation_id : $this->student->designation_id,
                        ],
                        'receivers' => $reply->communicationbook->messages->map(function ($message) {
                            return [
                                'id' => $message->receiver_type === "student" ? $message->student->id : $message->staff->id,
                                'campus' => $message->receiver_type === "student" ? $message->student->campus : $message->staff->campus,
                                'first_name' => $message->receiver_type === "student" ? $message->student->firstname : $message->staff->firstname,
                                'last_name' => $message->receiver_type === "student" ? $message->student->surname : $message->staff->surname,
                                'email' => $message->receiver_type === "student" ? $message->student->email_address : $message->staff->email,
                                'designation' => $message->receiver_type === "student" ? $message->student->designation_id : $message->staff->designation_id,
                            ];
                        })->toArray(),
                    ];
                })->flatMap(function ($reply) {
                    return $reply;
                })->toArray(),
            ]
        ];
    }
}
