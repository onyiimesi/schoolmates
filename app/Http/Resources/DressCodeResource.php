<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DressCodeResource extends JsonResource
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
                'day' => (string)$this->day,
                'wear' => (string)$this->wear,
                'description' => (string)$this->description,
            ]
        ];
    }
}
