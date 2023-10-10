<?php

namespace App\Http\Resources;

use App\Models\Staff;
use Illuminate\Http\Resources\Json\JsonResource;

class MidTermResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $teacher = Staff::where('id', $this->teacher_id)->first();
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'student_id' => (string)$this->student_id,
                'student_fullname' => (string)$this->student_fullname,
                'admission_number' => (string)$this->admission_number,
                'class_name' => (string)$this->class_name,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'results' => $this->studentscore->map(function($score) {
                    return [
                        "subject" => $score->subject,
                        "score" => $score->score
                    ];
                })->toArray(),
                'computed_midterm' => (string)$this->computed_midterm,
                'teacher_comment' => (string)$this->teacher_comment,
                'teacher_fullname' => $teacher->surname .' '. $teacher->firstname,
                'teacher_signature' => $teacher->signature,
            ]
        ];
    }
}
