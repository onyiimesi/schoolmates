<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolsResource extends JsonResource
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
                'schname' => (string)$this->schname,
                'schaddr' => (string)$this->schaddr,
                'schphone' => (string)$this->schphone,
                'schemail' => (string)$this->schemail,
                'schmotto' => (string)$this->schmotto,
                'schwebsite' => (string)$this->schwebsite,
                'schlogo' => (string)$this->schlogo,
            ]
        ];
    }
}
