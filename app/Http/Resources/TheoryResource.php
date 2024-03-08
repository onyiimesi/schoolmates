<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TheoryResource extends JsonResource
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
                'session' => (string)$this->session,
                'staff' => (string)$this->staff->surname .' '. $this->staff->firstname,
                'question_type' => (string)$this->question_type,
                'question' => (string)$this->question,
                'question' => (string)$this->question,
                'question_number' => (string)$this->question_number,
                'answer' => (string)$this->answer,
                'subject_id' => (string)$this->subject_id,
                'subject' => (string)$this->subject->subject,
                'image' => (string)$this->image,
                'total_question' => (string)$this->total_question,
                'question_mark' => (string)$this->question_mark,
                'total_mark' => (string)$this->total_mark,
                'week' => (string)$this->week
            ]
        ];
    }
}
