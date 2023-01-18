<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesReportResource extends JsonResource
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
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'expense_category' => (string)$this->expense_category,
                'bank_name' => (string)$this->bank_name,
                'account_name' => (string)$this->account_name,
                'payment_type' => (string)$this->payment_type,
                'beneficiary' => (string)$this->beneficiary,
                'transaction_id' => (string)$this->transaction_id,
                'amount' => (string)$this->amount,
                'purpose' => (string)$this->purpose,
            ]
        ];
    }
}
