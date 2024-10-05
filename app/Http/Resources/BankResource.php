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
            ],
            'payments' => $this->payments ? $this->payments->map(function ($payment) {
                return [
                    'id' => (int)$payment->id,
                    'student_id' => (int)$payment->student_id,
                    'student_fullname' => (string)$payment->student_fullname,
                    'admission_number' => $payment->invoice->admission_number,
                    'payment_method' => (string)$payment->payment_method,
                    'amount_paid' => (string)$payment->amount_paid,
                    'total_amount' => (string)$payment->total_amount,
                    'amount_due' => (string)$payment->amount_due,
                    'invoice_number' => $payment->invoice->invoice_no,
                    'date' => $payment->created_at,
                    'status' => (string)$payment->status,
                ];
            })->toArray() : [],
        ];
    }
}
