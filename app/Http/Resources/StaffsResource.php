<?php

namespace App\Http\Resources;

use App\Models\Pricing;
use App\Models\SchoolPayment;
use App\Models\Schools;
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
        $plan = SchoolPayment::where('sch_id', $this->sch_id)->first();
        if($plan){
            $getplan = Pricing::where('id', $plan->pricing_id)->first();

        } else {
            $school = Schools::where('sch_id', $this->sch_id)->first();
            $getplan = Pricing::where('id', $school->pricing_id)->first();
        }

        return [
            'id' => (string)$this->id,
            'attributes' => [
                'sch_id' => (string)$this->sch_id,
                'campus' => (string)$this->campus,
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
                'class_assigned' => (string)$this->class_assigned,
                'campus_type' => (string)$this->campus_type,
                'is_preschool' => (string)$this->is_preschool,
                'signature' => (string)$this->signature,
                'teacher_type' => (string)$this->teacher_type,
                'status' => (string)$this->status,
                'subjects' => $this->subjectteacher->flatMap(function($item){
                    return $item->subject;
                }),
                'plan' => (string)$getplan->plan
            ]
        ];
    }
}
