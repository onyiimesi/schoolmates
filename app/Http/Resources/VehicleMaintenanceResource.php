<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleMaintenanceResource extends JsonResource
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
                'staff_id' => (string)$this->staff_id,
                'vehicle_type' => (string)$this->vehicle_type,
                'vehicle_make' => (string)$this->vehicle_make,
                'vehicle_number' => (string)$this->vehicle_number,
                'driver_name' => (string)$this->driver_name,
                'detected_fault' => (string)$this->detected_fault,
                'mechanic_name' => (string)$this->mechanic_name,
                'mechanic_phone' => (string)$this->mechanic_phone,
                'cost_of_maintenance' => (string)$this->cost_of_maintenance,
                'initial_payment' => (string)$this->initial_payment
            ]
        ];
    }
}
