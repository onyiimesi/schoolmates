<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlipClassAssessmentResultResource extends JsonResource
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
                'flip_class_assessment_id' => (string)$this->flip_class_assessment_id,
                'student_id' => (string)$this->student_id,
                'subject_id' => (string)$this->subject_id,
                'question_type' => (string)$this->question_type,
                'student_mark' => (string)$this->student_mark,
                'total_mark' => (string)$this->total_mark,
                'score' => (string)$this->score,
                'week' => (string)$this->week
            ]
        ];
    }
}
