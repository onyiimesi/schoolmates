<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'designation_id' => (string)$this->designation_id,
            'department' => (string)$this->department,
            'surname' => (string)$this->surname,
            'firstname' => (string)$this->firstname,
            'middlename' => (string)$this->middlename,
            'username' => (string)$this->username,
            'email' => (string)$this->email,
            'phoneno' => (string)$this->phoneno,
            'address' => (string)$this->address,
            'image' => (string)$this->image,
            'status' => (string)$this->status,
        ];
    }
}
