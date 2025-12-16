<?php

namespace App\Http\Resources;

use App\Models\ClassModel;
use App\Models\Pricing;
use App\Models\SchoolPayment;
use App\Models\Schools;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentLoginResource extends JsonResource
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

        $classid = ClassModel::where([
            "sch_id" => $this->sch_id,
            "campus" => $this->campus,
            "class_name" => $this->present_class
        ])->value('id');

        return [
            'id' => (string)$this->id,
            'sch_id' => (string)$this->sch_id,
            'campus' => (string)$this->campus,
            'designation_id' => (string)$this->designation_id,
            'surname' => (string)$this->surname,
            'firstname' => (string)$this->firstname,
            'middlename' => (string)$this->middlename,
            'admission_number' => (string)$this->admission_number,
            'username' => (string)$this->username,
            'image' => (string)$this->image,
            'phone_number' => (string)$this->phone_number,
            'email_address' => (string)$this->email_address,
            'genotype' => (string)$this->genotype,
            'blood_group' => (string)$this->blood_group,
            'gender' => (string)$this->gender,
            'dob' => (string)$this->dob,
            'nationality' => (string)$this->nationality,
            'state' => (string)$this->state,
            'session_admitted' => (string)$this->session_admitted,
            'class' => (string)$this->class,
            'class_id' => $classid,
            'present_class' => (string)$this->present_class,
            'home_address' => (string)$this->home_address,
            'status' => (string)$this->status,
            'is_preschool' => (string) $this->is_preschool,
            'plan' => (string)$getplan->plan,
            'hos' => (object) [
                'id' => (int) $this->hos?->id,
                'name' => "{$this->hos?->surname} {$this->hos?->firstname} {$this->hos?->middlename}",
                'signature' => (string) $this->hos?->signature,
            ],
            'school' => (object) [
                'schname' => (string) $this->school?->schname,
                'schaddr' => (string) $this->school?->schaddr,
                'schphone' => (string) $this->school?->schphone,
                'schemail' => (string) $this->school?->schemail,
                'schmotto' => (string) $this->school?->schmotto,
                'schwebsite' => (string) $this->school?->schwebsite,
                'schlogo' => (string) $this->school?->schlogo,
                'country' => (string) $this->school?->country,
                'dos' => (string) $this->school?->dos,
                'signed_up' => (string) $this->school?->signed_up,
                'auto_generate' => $this->school?->auto_generate,
                'admission_number_initial' => (string) $this->school?->admission_number_initial,
                'status' => (string) $this->school?->status,
            ],
        ];
    }
}
