<?php

namespace App\Http\Resources;

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
        $jsonString = json_encode($this->results);
        $jsonStrings = json_encode($this->affective_disposition);
        $jsonStringss = json_encode($this->psychomotor_skills);
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
                'results' => json_decode($jsonString, JSON_UNESCAPED_SLASHES),
                'affective_disposition' => json_decode($jsonStrings, JSON_UNESCAPED_SLASHES),
                'psychomotor_skills' => json_decode($jsonStringss, JSON_UNESCAPED_SLASHES),
                'teacher_comment' => (string)$this->teacher_comment,
                'teacher_fullname' => (string)$this->teacher_fullname,
                'hos_comment' => (string)$this->hos_comment,
                'hos_fullname' => (string)$this->hos_fullname,
                'computed_endterm' => (string)$this->computed_endterm,
                // 'total' => (string)$this->total,
                // 'grade' => (string)$this->grade,
                // 'remark' => (string)$this->remark,
            ]
        ];
    }
}
