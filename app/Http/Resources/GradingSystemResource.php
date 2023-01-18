<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GradingSystemResource extends JsonResource
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
                'score_from' => (string)$this->score_from,
                'score_to' => (string)$this->score_to,
                'grade' => (string)$this->grade,
                'remark' => (string)$this->remark,
            ]
        ];
    }
}
