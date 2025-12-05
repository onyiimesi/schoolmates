<?php

namespace App\Traits;

use App\Enum\StudentStatus;
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
        $data = Student::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('present_class', $class)
            ->where('status', StudentStatus::ACTIVE)
            ->get();

        return StudentResource::collection($data);
    }

    public function getSubjects($user, $request)
    {
        $data = ClassModel::with(['subjects' => function ($query) use($request) {
                $query->where('session', $request['session']);
            }])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $request['class'])
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

    protected function getResultsForStudent($request)
    {
        return Result::with('studentscore')
            ->where('student_id', $request->student_id)
            ->where('class_name', $request->class_name)
            ->where('session', $request->session)
            ->get();
    }

    protected function getAllResultsForClass($request)
    {
        return Result::with('studentscore')
            ->where('class_name', $request->class_name)
            ->where('session', $request->session)
            ->get();
    }

    protected function calculateTotalScore($results, $studentSubjectCount = false)
    {
        $total = 0;
        $count = 0;

        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                $total += $score->score;
                if ($studentSubjectCount && $result->period === "Second Half" && $score->score > 0) {
                    $count++;
                }
            }
        }

        return ['total' => $total, 'count' => $count];
    }

    protected function calculateAverage($total, $count)
    {
        return $count > 0 ? $total / $count : 0;
    }
}



