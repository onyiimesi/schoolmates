<?php

namespace App\Http\Resources;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PreSchoolResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $jsonString = json_encode($this->results);
        $jsonStrings = json_encode($this->evaluation_report);
        $jsonStringss = json_encode($this->cognitive_development);
        $signature = Staff::where('class_assigned', $this->class_name)->get();
        $student_image = Student::where('id', $this->student_id)->first();
        $teacher = Auth::user();
        $hosId = Staff::where('campus', $teacher->campus)
            ->where('designation_id', 3)
            ->where('status', 'Active')
            ->first();
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'student_id' => (string)$this->student_id,
                'student_fullname' => (string)$this->student_fullname,
                'student_image' => (string)$student_image->image,
                'admission_number' => (string)$this->admission_number,
                'class_name' => (string)$this->class_name,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'school_opened' => (string)$this->school_opened,
                'times_present' => (string)$this->times_present,
                'times_absent' => (string)$this->times_absent,
                'results' => json_decode($jsonString, JSON_UNESCAPED_SLASHES),
                'evaluation_report' => json_decode($jsonStrings, JSON_UNESCAPED_SLASHES),
                'cognitive_development' => json_decode($jsonStringss, JSON_UNESCAPED_SLASHES),
                'extra_curricular_activities' => $this->preschoolresultextracurricular->map(function($value) {
                    return [
                        "name" => $value->name,
                        "value" => $value->value
                    ];
                })->toArray(),
                'teacher_comment' => (string)$this->teacher_comment,
                'teacher_id' => (string)$this->teacher_id,
                'teachers' => $signature->map(function($teacher) {
                    return [
                        "name" => $teacher->surname .' '. $teacher->firstname,
                        "signature" => $teacher->signature
                    ];
                })->toArray(),
                'hos_comment' => (string)$this->hos_comment,
                'hos_id' => (string)$this->hos_id,
                'hos_fullname' => (string)$this->hos_fullname,
                'hos_signature' => $hosId?->signature,
                'status' => (string)$this->status,
            ]
        ];
    }
}
