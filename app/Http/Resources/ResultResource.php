<?php

namespace App\Http\Resources;

use App\Enum\StaffStatus;
use App\Models\ClassModel;
use App\Models\GradingSystem;
use App\Models\Result;
use App\Models\Schools;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentScore;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $signature = Staff::where([
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'class_assigned' => $this->class_name,
            'status' => StaffStatus::ACTIVE,
        ])->get();

        $dos = Schools::where('sch_id', $this->sch_id)->first('dos');

        $student_image = Student::where([
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'id' => $this->student_id
        ])->firstOrFail();

        $class = ClassModel::where([
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'class_name' => $this->class_name
        ])->firstOrFail();

        $classCount = ClassModel::where([
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'class_name' => $this->class_name
        ])->count();

        if($class->class_type === "upper"){
            $hod = Staff::where([
                'sch_id' => $this->sch_id,
                'campus' => $this->campus,
                'class_type' => 'upper',
                'designation_id' => 3
            ])->get();
        }elseif($class->class_type === "lower"){
            $hod = Staff::where([
                'sch_id' => $this->sch_id,
                'campus' => $this->campus,
                'class_type' => 'lower',
                'designation_id' => 3
            ])->get();
        } else {
            $hod = Staff::where([
                'sch_id' => $this->sch_id,
                'campus' => $this->campus,
                'designation_id' => 3
            ])->get();
        }

        $classTotalScore = StudentScore::whereHas('result', function ($query) {
            $query->where([
                'sch_id' => $this->sch_id,
                'campus' => $this->campus,
                'class_name' => $this->class_name,
                'term' => $this->term,
                'session' => $this->session
            ]);
        })->sum('score');

        $totalStudentsInClass = Result::where([
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'class_name' => $this->class_name,
            'term' => $this->term,
            'session' => $this->session
        ])->count();

        $totalSubjects = $this->studentscore->filter(function($score) {
            return $score->score != 0;
        })->count();

        $classAverage = ($totalStudentsInClass > 0 && $totalSubjects > 0)
        ? round($classTotalScore / ($totalStudentsInClass * $totalSubjects), 2)
        : 0;

        $classGrade = GradingSystem::where('sch_id', $this->sch_id)
            ->where('campus', $this->campus)
            ->where('score_to', '>=', $classAverage)
            ->first();

        $grade = $classAverage > 90 ? "EXCELLENT" : ($classGrade->remark ?? "");

        // $totalScore = $this->studentscore->filter(function($score) {
        //     return $score->score != 0;
        // })->sum('score');

        $studentResults = Result::with(['studentscore'])
            ->where([
                'sch_id' => $this->sch_id,
                'campus' => $this->campus,
                'student_id' => $this->student_id,
                'class_name' => $this->class_name,
                'term' => $this->term,
                'session' => $this->session,
            ])->get();

        $totalScore = 0;
        $totalSubjects = 0;

        foreach ($studentResults as $result) {
            foreach ($result->studentscore as $score) {
                $totalScore += $score->score;
                if ($result->period === "Second Half" && $score->score > 0) {
                    $totalSubjects++;
                }
            }
        }

        $maxScorePerSubject = 100;
        $totalObtainableMarks = $totalScore / $maxScorePerSubject;
        $gpa = ($totalObtainableMarks > 0) ? $totalObtainableMarks * $totalSubjects : 0;

        return [
            'id' => (string)$this->id,
            'attributes' => [
                'campus' => (string)$this->campus,
                'campus_type' => (string)$this->campus_type,
                'student_id' => (string)$this->student_id,
                'student_fullname' => (string)$this->student_fullname,
                'student_image' => (string)$student_image?->image,
                'admission_number' => (string)$this->admission_number,
                'gender' => (string)$this->student?->gender,
                'class_name' => (string)$this->class_name,
                'period' => (string)$this->period,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'school_opened' => (string)$this->school_opened,
                'times_present' => (string)$this->times_present,
                'times_absent' => (string)$this->times_absent,
                'number_in_class' => $classCount,
                'results' => $this->studentscore ? $this->studentscore->filter(function($score) {
                    return $score->score != 0;
                })->map(function($score) {
                    return [
                        "subject" => $score->subject,
                        "score" => $score->score
                    ];
                })->toArray() : [],
                'total_subjects' => $totalSubjects,
                'total_score' => $totalScore,
                'student_average' => $totalSubjects > 0 ? round($totalScore / $totalSubjects, 2) : 0,
                'class_average' => $classAverage,
                'class_grade' => $grade,
                'gpa' => $gpa,
                'affective_disposition' => $this->affectivedisposition->map(function($score) {
                    return [
                        "name" => $score->name,
                        "score" => $score->score
                    ];
                })->toArray(),
                'psychomotor_skills' => $this->psychomotorskill->map(function($score) {
                    return [
                        "name" => $score->name,
                        "score" => $score->score
                    ];
                })->toArray(),
                'extra_curricular_activities' => $this->resultextracurricular->map(function($value) {
                    return [
                        "name" => $value->name,
                        "value" => $value->value
                    ];
                })->toArray(),
                'abacus' => (object)[
                    "name" => $this->abacus?->name
                ],
                'psychomotor_performance' => $this->psychomotorperformance->map(function($score) {
                    return [
                        "name" => $score->name,
                        "score" => $score->score
                    ];
                })->toArray(),
                'pupil_report' => $this->pupilreport->map(function($score) {
                    return [
                        "name" => $score->name,
                        "score" => $score->score
                    ];
                })->toArray(),
                'teacher_comment' => $this->teacher_comment,
                'teachers' => $signature->map(function($teacher) {
                    return [
                        "name" => $teacher->surname .' '. $teacher->firstname,
                        "signature" => $teacher->signature
                    ];
                })->toArray(),
                'hos' => $hod ? $hod->map(function($hods) {
                    return [
                        "name" => $hods->surname .' '. $hods->firstname,
                        "signature" => $hods->signature
                    ];
                })->toArray() : [],
                'performance_remark' => (string)$this->performance_remark,
                'hos_comment' => (string)$this->hos_comment,
                'hos_fullname' => (string)$this->hos_fullname,
                'computed_endterm' => (string)$this->computed_endterm,
                'dos' => $dos->dos,
                'status' => (string)$this->status
            ]
        ];
    }
}
