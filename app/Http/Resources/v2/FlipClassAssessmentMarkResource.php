<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlipClassAssessmentMarkResource extends JsonResource
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
                'student_id' => (string)$this->student_id,
                'subject_id' => (string)$this->subject_id,
                'flip_class_assessment_id' => (string)$this->flip_class_assessment_id,
                'topic' => (string)$this->topic,
                'question_type' => (string)$this->question_type,
                'question' => (string)$this->question,
                'question_number' => (string)$this->question_number,
                'answer' => (string)$this->answer,
                'correct_answer' => (string)$this->correct_answer,
                'submitted' => (string)$this->submitted,
                'mark' => (string)$this->mark,
                'teacher_mark' => (string)$this->teacher_mark,
                'total_question' => (string)$this->flipClassAssessment?->total_question,
                'question_mark' => (string)$this->flipClassAssessment?->question_mark,
                'total_mark' => (string)$this->flipClassAssessment?->total_mark,
                'week' => (string)$this->week
            ]
        ];
    }
}
