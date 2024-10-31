<?php

namespace App\Http\Resources;

use App\Models\Staff;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MidTermResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $auth = Auth::user();

        $signature = Staff::where('sch_id', $auth->sch_id)
            ->where('campus', $auth->campus)
            ->where('class_assigned', $this->class_name)->get();

        return [
            'id' => (string)$this->id,
            'attributes' => [
                'student_id' => (string)$this->student_id,
                'student_fullname' => (string)$this->student_fullname,
                'admission_number' => (string)$this->admission_number,
                'class_name' => (string)$this->class_name,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'result_type' => (string)$this->result_type,
                'results' => $this->studentscore->filter(function($score) {
                    return $score->score != 0;
                })->map(function($score) {
                    return [
                        "subject" => $score->subject,
                        "score" => $score->score
                    ];
                })->toArray(),
                'computed_midterm' => (string)$this->computed_midterm,
                'teacher_comment' => (string)$this->teacher_comment,
                'teachers' => $signature->map(function($teacher) {
                    return [
                        "name" => $teacher->surname .' '. $teacher->firstname,
                        "signature" => $teacher->signature
                    ];
                })->toArray(),
                'status' => (string)$this->status
            ]
        ];
    }
}
