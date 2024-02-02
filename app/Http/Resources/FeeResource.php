<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeeResource extends JsonResource
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
                'feetype' => (string)$this->feetype,
                'amount' => (string)$this->amount,
                'term' => (string)$this->term,
                'fee_status' => (string)$this->fee_status,
                'category' => (string)$this->category,
            ]
        ];
    }
}
