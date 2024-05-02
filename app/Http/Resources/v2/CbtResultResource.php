<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CbtResultResource extends JsonResource
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
                'question_type' => (string)$this->question_type,
                'answer_score' => (array)$this->answer_score,
                'correct_answer' => (string)$this->correct_answer,
                'incorrect_answer' => (string)$this->incorrect_answer,
                'total_answer' => (string)$this->total_answer,
                'student_total_mark' => (string)$this->student_total_mark,
                'test_total_mark' => (string)$this->test_total_mark,
                'student_duration' => (string)$this->student_duration,
                'test_duration' => (string)$this->test_duration
            ]
        ];
    }
}
