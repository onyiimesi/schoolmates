<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthReportResource extends JsonResource
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
                'admission_number' => (string)$this->admission_number,
                'student_id' => (string)$this->student_id,
                'student_fullname' => (string)$this->student_fullname,
                'date_of_incident' => (string)$this->date_of_incident,
                'time_of_incident' => (string)$this->time_of_incident,
                'condition' => (string)$this->condition,
                'state' => (string)$this->state,
                'report_details' => (string)$this->report_details,
                'action_taken' => (string)$this->action_taken,
                'recommendation' => (string)$this->recommendation,
            ]
        ];
    }
}
