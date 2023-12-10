<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaximumScoresResource extends JsonResource
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
                'midterm' => (string)$this->midterm,
                'first_assessment' => (string)$this->first_assessment,
                'second_assessment' => (string)$this->second_assessment,
                'has_two_assessment' => (boolean)$this->has_two_assessment,
                'exam' => (string)$this->exam,
                'total' => (string)$this->total,
            ]
        ];
    }
}
