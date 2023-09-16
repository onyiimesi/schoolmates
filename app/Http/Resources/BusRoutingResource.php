<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusRoutingResource extends JsonResource
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
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'admission_number' => (string)$this->admission_number,
                'student_id' => (string)$this->student_id,
                'bus_type' => (string)$this->bus_type,
                'bus_number' => (string)$this->bus_number,
                'driver_name' => (string)$this->driver_name,
                'driver_phonenumber' => (string)$this->driver_phonenumber,
                'driver_image' => (string)$this->driver_image,
                'conductor_name' => (string)$this->conductor_name,
                'conductor_phonenumber' => (string)$this->conductor_phonenumber,
                'conductor_image' => (string)$this->conductor_image,
                'route' => (string)$this->route,
                'ways' => (string)$this->ways,
                'pickup_time' => (string)$this->pickup_time,
                'dropoff_time' => (string)$this->dropoff_time
            ]
        ];
    }
}
