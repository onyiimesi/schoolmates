<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $jsonString = json_encode($this->data);
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'attendance_date' => (string)$this->attendance_date,
                'data' => json_decode($jsonString, JSON_UNESCAPED_SLASHES),
                // 'admission_number' => (string)$this->admission_number,
                // 'student_fullname' => (string)$this->student_fullname,
                'class' => (string)$this->class,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                
            ]
        ];
    }
}
