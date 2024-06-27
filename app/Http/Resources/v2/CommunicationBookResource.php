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
            ]
        ];
    }
}
