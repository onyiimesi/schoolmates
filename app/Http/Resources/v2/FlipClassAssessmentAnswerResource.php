<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlipClassAssessmentAnswerResource extends JsonResource
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
                'student' => (string)$this->student?->surname .' '. $this->student?->firstname,
                'student_id' => (string)$this->student_id,
                'flip_class_assessment_id' => (string)$this->flip_class_assessment_id,
                'topic' => (string)$this->topic,
                'question_type' => (string)$this->question_type,
                'question' => (string)$this->question,
                'question_number' => (string)$this->question_number,
                'answer' => (string)$this->answer,
                'subject' => (string)$this->subject?->subject,
                'subject_id' => (string)$this->subject_id,
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
