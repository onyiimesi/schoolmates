<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
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
                'bank_name' => (string)$this->bank_name,
                'account_name' => (string)$this->account_name,
                'opening_balance' => (string)$this->opening_balance,
                'account_number' => (string)$this->account_number,
                'account_purpose' => (string)$this->account_purpose
            ]
        ];
    }
}
