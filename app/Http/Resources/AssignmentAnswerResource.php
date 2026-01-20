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
                'student' => (string)$this->student?->surname .' '. $this->student?->firstname,
                'student_id' => (string)$this->student_id,
                'assignment_id' => (string)$this->assignment_id,
                'question_type' => (string)$this->question_type,
                'question' => (string)$this->question,
                'question_number' => (string)$this->question_number,
                'answer' => (string)$this->answer,
                'subject' => (string)$this->subject?->subject,
                'subject_id' => $this->subject_class_id,
                'correct_answer' => (string)$this->correct_answer,
                'mark' => (string)$this->mark,
                'submitted' => (string)$this->submitted,
                'total_question' => (string)$this->assignment?->total_question,
                'question_mark' => (string)$this->assignment?->question_mark,
                'total_mark' => (string)$this->assignment?->total_mark,
                'week' => (string)$this->week
            ]
        ];
    }
}
