<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
                'staff' => (string)"{$this->staff->surname} {$this->staff->firstname}",
                'question_type' => (string)$this->question_type,
                'question' => (string)$this->question,
                'question_number' => (string)$this->question_number,
                'answer' => (string)$this->answer,
                'subject_id' => $this->subject_class_id,
                'subject' => (string)$this->subjectClass?->subject,
                'option1' => (string)$this->option1,
                'option2' => (string)$this->option2,
                'option3' => (string)$this->option3,
                'option4' => (string)$this->option4,
                'total_question' => (string)$this->total_question,
                'question_mark' => (string)$this->question_mark,
                'total_mark' => (string)$this->total_mark,
                'week' => (string)$this->week,
                'status' => (string)$this->status
            ]
        ];
    }
}
