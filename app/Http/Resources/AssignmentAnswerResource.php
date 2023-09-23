<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentAnswerResource extends JsonResource
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
                'student' => (string)$this->student->surname .' '. $this->student->firstname,
                'student_id' => (string)$this->student_id,
                'question_type' => (string)$this->question_type,
                'question' => (string)$this->question,
                'answer' => (string)$this->answer,
                'subject' => (string)$this->subject->subject,
                'subject_id' => (string)$this->subject_id,
                'correct_answer' => (string)$this->correct_answer,
                'mark' => (string)$this->mark
            ]
        ];
    }
}
