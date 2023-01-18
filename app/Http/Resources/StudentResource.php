<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
                'surname' => (string)$this->surname,
                'firstname' => (string)$this->firstname,
                'middlename' => (string)$this->middlename,
                'admission_number' => (string)$this->admission_number,
                'username' => (string)$this->username,
                'genotype' => (string)$this->genotype,
                'blood_group' => (string)$this->blood_group,
                'gender' => (string)$this->gender,
                'dob' => (string)$this->dob,
                'nationality' => (string)$this->nationality,
                'state' => (string)$this->state,
                'session_admitted' => (string)$this->session_admitted,
                'class' => (string)$this->class,
                'class_sub_class' => (string)$this->class_sub_class,
                'present_class' => (string)$this->present_class,
                'sub_class' => (string)$this->sub_class,
                'image' => (string)$this->image,
                'home_address' => (string)$this->home_address,
                'phone_number' => (string)$this->phone_number,
                'email_address' => (string)$this->email_address,
                'status' => (string)$this->status,
            ]
        ];
    }
}