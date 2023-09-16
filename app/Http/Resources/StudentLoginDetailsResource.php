<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentLoginDetailsResource extends JsonResource
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
            'surname' => (string)$this->surname,
            'firstname' => (string)$this->firstname,
            'middlename' => (string)$this->middlename,
            'admission_number' => (string)$this->admission_number,
            'username' => (string)$this->username,
            'present_class' => (string)$this->present_class,
            'sub_class' => (string)$this->sub_class,
            'pass_word' => (string)$this->pass_word,
        ];
    }
}
