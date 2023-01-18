<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
                'bank_name' => (string)$this->bank_name,
                'account_name' => (string)$this->account_name,
                'student_fullname' => (string)$this->student_fullname,
                'payment_method' => (string)$this->payment_method,
                'amount_paid' => (string)$this->amount_paid,
                'total_amount' => (string)$this->total_amount,
                'amount_due' => (string)$this->amount_due,
                'remark' => (string)$this->remark,
                'status' => (string)$this->status,
            ]
        ];
    }
}
