<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
                'admission_number' => (string)$this->admission_number,
                'student_id' => (string)$this->student_id,
                'fullname' => (string)$this->fullname,
                'class' => (string)$this->class,
                'feetype' => (string)$this->feetype,
                'amount' => (string)$this->amount,
                'notation' => (string)$this->notation,
                'discount' => (string)$this->discount,
                'discount_amount' => (string)$this->discount_amount,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'invoice_no' => (string)$this->invoice_no,
            ]
        ];
    }
}
