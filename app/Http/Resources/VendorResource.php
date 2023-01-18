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
                'vendor_code' => (string)$this->vendor_code,
                'vendor_type' => (string)$this->vendor_type,
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
