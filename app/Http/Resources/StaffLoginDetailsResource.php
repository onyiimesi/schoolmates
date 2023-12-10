<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffLoginDetailsResource extends JsonResource
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
                'designation_id' => (string)$this->designation_id,
                'surname' => (string)$this->surname,
                'firstname' => (string)$this->firstname,
                'middlename' => (string)$this->middlename,
                'username' => (string)$this->username,
                'pass_word' => (string)$this->pass_word,
                'class_assigned' => (string)$this->class_assigned,
                'teacher_type' => (string)$this->teacher_type,
                'subjects' => $this->subjectteacher->flatMap(function($item){
                    return $item->subject;
                })
            ]
        ];
    }
}
