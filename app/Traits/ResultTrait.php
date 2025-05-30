<?php

namespace App\Traits;

use App\Http\Resources\GradingSystemResource;
use App\Http\Resources\MidTermResultResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\SubjectClassResource;
use App\Models\ClassModel;
use App\Models\ExtraCurricular;
use App\Models\GradingSystem;
use App\Models\Result;
use App\Models\SchoolScoreSetting;
use App\Models\Student;

trait ResultTrait
{
    public function getStudentsByClass($user, $class)
    {
        $data = Student::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'present_class' => $class,
            ])
            ->get();

        return StudentResource::collection($data);
    }

    public function getSubjects($user, $class)
    {
        $data = ClassModel::with('subjects')
            ->where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'class_name' => $class,
            ])
            ->get();

        return SubjectClassResource::collection($data);
    }

    public function getGrading($user)
    {
        return GradingSystemResource::collection(
            GradingSystem::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );
    }

    public function getExtraCurricular($user)
    {
        return ExtraCurricular::select('id', 'name')
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get();
    }

    public function getScoreSetting($user)
    {
        return SchoolScoreSetting::with('scoreOption')
            ->byCampus($user)
            ->first();
    }

    protected function getAssessmentResults($user, $params, string $resultType)
    {
        return MidTermResultResource::collection(
            Result::with(['student', 'studentscore'])
                ->where([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'student_id' => $params['student_id'],
                    'term' => $params['term'],
                    'session' => $params['session'],
                    'result_type' => $resultType,
                ])
                ->get()
        );
    }

}



