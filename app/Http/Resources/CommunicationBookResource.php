<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunicationBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'sch_id' => (string)$this->sch_id,
                'campus' => (string)$this->campus,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'title' => (string)$this->title,
                'urgency' => (string)$this->urgency,
                'student_id' => (string)$this->student_id,
                'admission_number' => (string)$this->admission_number,
                'message' => (string)$this->message,
                'sender' => (string)$this->sender,
                'status' => (string)$this->status,
            ]
        ];
    }
}
