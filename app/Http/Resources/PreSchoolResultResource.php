<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PreSchoolResultResource extends JsonResource
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
        $jsonStrings = json_encode($this->evaluation_report);
        $jsonStringss = json_encode($this->cognitive_development);
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
                'school_opened' => (string)$this->school_opened,
                'times_present' => (string)$this->times_present,
                'times_absent' => (string)$this->times_absent,
                'results' => json_decode($jsonString, JSON_UNESCAPED_SLASHES),
                'evaluation_report' => json_decode($jsonStrings, JSON_UNESCAPED_SLASHES),
                'cognitive_development' => json_decode($jsonStringss, JSON_UNESCAPED_SLASHES),
                'teacher_comment' => (string)$this->teacher_comment,
                'teacher_id' => (string)$this->teacher_id,
                'hos_comment' => (string)$this->hos_comment,
                'hos_id' => (string)$this->hos_id,
                'status' => (string)$this->status,
            ]
        ];
    }
}
