<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectClassResource extends JsonResource
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
            'attributes' => [
                'campus' => (string) $this->campus,
                'class' => (string) $this->class_name,
                'subject' => $this->subjects->map(function($name) {
                    return [
                        "id" => $name->id,
                        "name" => $name->subject
                    ];
                })->toArray(),
            ]
        ];
    }
}
