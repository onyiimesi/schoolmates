<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StationarySaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'attributes' => [
                'class_id' => $this->class_id,
                'student_id' => $this->student_id,
                'date' => $this->date,
                'quantity' => $this->quantity,
                'created_at' => $this->created_at->toDateString()
            ],
            'relationships' => [
                'class' => $this->whenLoaded('class', fn() => [
                    'id' => $this->class->id,
                    'name' => $this->class->class_name
                ]),
                'student' => $this->whenLoaded('student', fn() => [
                    'id' => $this->student->id,
                    'first_name' => $this->student->firstname,
                    'middle_name' => $this->student->middlename,
                    'last_name' => $this->student->surname,
                    'admission_number' => $this->student->admission_number,
                    'present_class' => $this->student->present_class
                ])
            ]
        ];
    }
}
