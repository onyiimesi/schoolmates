<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
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
                'vendor_code' => (string)$this->vendor_code,
                'vendor_type' => (string)$this->vendor_type,
                'initial_balance' => (string)$this->initial_balance,
                'vendor_name' => (string)$this->vendor_name,
                'company_name' => (string)$this->company_name,
                'contact_address' => (string)$this->contact_address,
                'contact_person' => (string)$this->contact_person,
                'contact_phone' => (string)$this->contact_phone,
                'email_address' => (string)$this->email_address,
            ]
        ];
    }
}
