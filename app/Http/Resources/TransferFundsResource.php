<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferFundsResource extends JsonResource
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
                'from' => (string)$this->from,
                'to' => (string)$this->to,
                'account' => (string)$this->account,
                'memo' => (string)$this->memo,
                'transfer_date' => (string)$this->transfer_date,
            ]
        ];
    }
}
