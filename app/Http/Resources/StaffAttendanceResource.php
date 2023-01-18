<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffAttendanceResource extends JsonResource
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
                'staff_id' => (string)$this->staff_id,
                'time_in' => (string)$this->time_in,
                'date_in' => (string)$this->date_in,
                'time_out' => (string)$this->time_out,
                'date_out' => (string)$this->date_out,
            ]
        ];
    }
}
