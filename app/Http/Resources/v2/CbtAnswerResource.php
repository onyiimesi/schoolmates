<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CbtAnswerResource extends JsonResource
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
                'session' => (string)$this->session,
                'cbt_question_id' => (string)$this->cbt_question_id,
                'student' => (string)$this->student?->surname .' '. $this->student?->firstname,
                'student_id' => (string)$this->student_id,
                'subject' => (string)$this->subject?->subject,
                'subject_id' => (string)$this->subject_id,
                'question' => (string)$this->question,
                'question_number' => (string)$this->question_number,
                'question_type' => (string)$this->question_type,
                'answer' => (string)$this->answer,
                'correct_answer' => (string)$this->correct_answer,
                'mark_status' => (string)$this->mark_status,
                'submitted' => (string)$this->submitted,
                'submitted_time' => (string)$this->submitted_time,
                'total_question' => (string)$this->cbtquestion?->total_question,
                'question_mark' => (string)$this->cbtquestion?->question_mark,
                'total_mark' => (string)$this->assignment?->total_mark,
                'duration' => (string)$this->duration
            ]
        ];
    }
}
