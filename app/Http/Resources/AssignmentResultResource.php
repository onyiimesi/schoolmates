<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResultResource extends JsonResource
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
                'assignment_id' => (string)$this->assignment_id,
                'student_id' => (string)$this->student_id,
                'subject_id' => $this->subject_class_id,
                'question_type' => (string)$this->question_type,
                'student_mark' => (string)$this->student_mark,
                'total_mark' => (string)$this->total_mark,
                'score' => (string)$this->score,
                'week' => (string)$this->week
            ]
        ];
    }
}
