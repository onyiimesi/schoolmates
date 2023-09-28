<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentMarkResource extends JsonResource
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
                'student_id' => (string)$this->student_id,
                'subject_id' => (string)$this->subject_id,
                'question_id' => (string)$this->question_id,
                'question_type' => (string)$this->question_type,
                'question' => (string)$this->question,
                'answer' => (string)$this->answer,
                'correct_answer' => (string)$this->correct_answer,
                'submitted' => (string)$this->submitted,
                'mark' => (string)$this->mark,
                'teacher_mark' => (string)$this->teacher_mark,
                'total_question' => (string)$this->assignment->total_question,
                'question_mark' => (string)$this->assignment->question_mark,
                'total_mark' => (string)$this->assignment->total_mark
            ]
        ];
    }
}
