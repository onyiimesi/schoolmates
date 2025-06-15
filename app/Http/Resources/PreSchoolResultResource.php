<?php

namespace App\Http\Resources;

use App\Enum\StaffStatus;
use App\Models\PreSchoolResult;
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
        $student = Student::find($this->student_id);
        $teacher = Auth::user();

        $classTeachers = Staff::where([
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'class_assigned' => $this->class_name,
            'status' => StaffStatus::ACTIVE,
        ])->get();

        $headOfSchool = Staff::where([
            'campus' => $teacher->campus,
            'designation_id' => 3,
            'status' => StaffStatus::ACTIVE,
        ])->first();

        $totalStudentsInClass = PreSchoolResult::where([
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'class_name' => $this->class_name,
            'term' => $this->term,
            'session' => $this->session,
        ])->count();

        return [
            'id' => (string)$this->id,
            'attributes' => [
                'student_id' => (string)$this->student_id,
                'student_fullname' => (string)$this->student_fullname,
                'student_image' => (string)$student->image,
                'admission_number' => (string)$this->admission_number,
                'class_name' => (string)$this->class_name,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'school_opened' => (string)$this->school_opened,
                'times_present' => (string)$this->times_present,
                'times_absent' => (string)$this->times_absent,
                'total_no_in_class' => $totalStudentsInClass,
                'evaluation_report' => $this->evaluation_report,
                'cognitive_development' => $this->cognitive_development,
                'extra_curricular_activities' => $this->preschoolresultextracurricular->map(fn($value) => [
                    "name" => $value->name,
                    "value" => $value->value
                ])->toArray(),
                'teacher_comment' => (string)$this->teacher_comment,
                'teacher_id' => (string)$this->teacher_id,
                'teachers' => $classTeachers->map(fn($teacher) => [
                    "name" => "{$teacher->surname} {$teacher->firstname}",
                    "signature" => $teacher->signature
                ])->toArray(),
                'hos_comment' => (string)$this->hos_comment,
                'hos_id' => (string)$this->hos_id,
                'hos_fullname' => (string)$this->hos_fullname,
                'hos_signature' => $headOfSchool?->signature,
                'status' => (string)$this->status,
            ]
        ];
    }
}
