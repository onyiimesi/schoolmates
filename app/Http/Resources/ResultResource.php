<?php

namespace App\Http\Resources;

use App\Models\Staff;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
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
                'campus' => (string)$this->campus,
                'campus_type' => (string)$this->campus_type,
                'student_id' => (string)$this->student_id,
                'student_fullname' => (string)$this->student_fullname,
                'admission_number' => (string)$this->admission_number,
                'class_name' => (string)$this->class_name,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'school_opened' => (string)$this->school_opened,
                'times_present' => (string)$this->times_present,
                'times_absent' => (string)$this->times_absent,
                'results' => $this->studentscore->map(function($score) {
                    return [
                        "subject" => $score->subject,
                        "score" => $score->score
                    ];
                })->toArray(),
                'affective_disposition' => $this->affectivedisposition->map(function($score) {
                    return [
                        "name" => $score->name,
                        "score" => $score->score
                    ];
                })->toArray(),
                'psychomotor_skills' => $this->psychomotorskill->map(function($score) {
                    return [
                        "name" => $score->name,
                        "score" => $score->score
                    ];
                })->toArray(),
                'teacher_comment' => $teacher->teacher_comment,
                'teacher_fullname' => $teacher->teacher_fullname,
                'teacher_signature' => $teacher->signature,
                'hos_comment' => (string)$this->hos_comment,
                'hos_fullname' => (string)$this->hos_fullname,
                'computed_endterm' => (string)$this->computed_endterm
            ]
        ];
    }
}
