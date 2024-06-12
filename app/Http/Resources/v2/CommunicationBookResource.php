<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'staff_id' => (int)$this->staff_id,
                'student_id' => (int)$this->student_id,
                'admission_number' => (string)$this->admission_number,
                'subject' => (string)$this->subject,
                'message' => (string)$this->message,
                'pinned' => (string)$this->pinned,
                'attachment' => (string)$this->file,
                'status' => (string)$this->status
            ]
        ];
    }
}
