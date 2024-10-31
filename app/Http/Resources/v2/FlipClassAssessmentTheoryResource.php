<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlipClassAssessmentTheoryResource extends JsonResource
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
                'staff' => (string)$this->staff->surname .' '. $this->staff->firstname,
                'topic' => (string)$this->topic,
                'question_type' => (string)$this->question_type,
                'question' => (string)$this->question,
                'question' => (string)$this->question,
                'question_number' => (string)$this->question_number,
                'answer' => (string)$this->answer,
                'subject_id' => (string)$this->subject_id,
                'subject' => (string)$this->subject->subject,
                'image' => (string)$this->image,
                'total_question' => (string)$this->total_question,
                'question_mark' => (string)$this->question_mark,
                'total_mark' => (string)$this->total_mark,
                'week' => (string)$this->week,
                'status' => (string)$this->status
            ]
        ];
    }
}
