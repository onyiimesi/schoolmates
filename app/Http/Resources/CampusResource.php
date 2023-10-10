<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampusResource extends JsonResource
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
                'name' => (string)$this->name,
                'email' => (string)$this->email,
                'image' => (string)$this->image,
                'phoneno' => (string)$this->phoneno,
                'address' => (string)$this->address,
                'state' => (string)$this->state,
                'campus_type' => (string)$this->campus_type,
                'is_preschool' => (string)$this->is_preschool,
                'status' => (string)$this->status,
            ]
        ];
    }
}
