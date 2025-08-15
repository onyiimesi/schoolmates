<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $plan = $this->school->schoolPayment->pricing ?? $this->school->pricing;

        return [
            'id' => (string) $this->id,
            'attributes' => [
                'sch_id' => (string) $this->sch_id,
                'campus' => (string) $this->campus,
                'designation_id' => (string) $this->designation_id,
                'designation' => (string) optional($this->designation)->designation_name,
                'department' => (string) $this->department,
                'surname' => (string) $this->surname,
                'firstname' => (string) $this->firstname,
                'middlename' => (string) $this->middlename,
                'username' => (string) $this->username,
                'email' => (string) $this->email,
                'phoneno' => (string) $this->phoneno,
                'gender' => (string) $this->gender,
                'address' => (string) $this->address,
                'image' => (string) $this->image,
                'class_assigned' => (string) $this->class_assigned,
                'pass_word' => (string) $this->pass_word,
                'campus_type' => (string) $this->campus_type,
                'is_preschool' => (string) $this->is_preschool,
                'signature' => (string) $this->signature,
                'teacher_type' => (string) $this->teacher_type,
                'status' => (string) $this->status,
                'subjects' => $this->subjectteacher->flatMap(fn($item) => $item->subject),
                'plan' => (string) $plan->plan
            ]
        ];
    }
}
