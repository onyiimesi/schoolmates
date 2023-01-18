<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleLogResource extends JsonResource
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
                'vehicle_number' => (string)$this->vehicle_number,
                'driver_name' => (string)$this->driver_name,
                'route' => (string)$this->route,
                'purpose' => (string)$this->purpose,
                'mechanic_condition' => (string)$this->mechanic_condition,
                'add_info' => (string)$this->add_info,
                'date_out' => (string)$this->date_out,
                'time_out' => (string)$this->time_out,
            ]
        ];
    }
}
