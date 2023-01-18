<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterSubjectResource extends JsonResource
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
                'admission_number' => (string)$this->admission_number,
                'student_fullname' => (string)$this->student_fullname,
                'class' => (string)$this->class,
                'sub_class' => (string)$this->sub_class,
                'subject' => (string)$this->subject,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
            ]
        ];
    }
}
