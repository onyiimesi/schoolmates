<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CbtQuestionResource extends JsonResource
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
                'subject_id' => (string)$this->subject_id,
                'question_type' => (string)$this->question_type,
                'question' => (string)$this->question,
                'option1' => (string)$this->option1,
                'option2' => (string)$this->option2,
                'option3' => (string)$this->option3,
                'option4' => (string)$this->option4,
                'answer' => (string)$this->answer,
                'question_mark' => (string)$this->question_mark,
                'question_number' => (string)$this->question_number,
                'status' => (string)$this->status,
            ]
        ];
    }
}
