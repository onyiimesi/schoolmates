<?php

namespace App\Services;

use App\Enum\PeriodicName;
use App\Http\Resources\MidTermResultResource;
use App\Http\Resources\ResultResource;
use App\Models\Result;
use App\Traits\HttpResponses;
use App\Traits\ResultTrait;

class GeneralResultService
{
    use HttpResponses, ResultTrait;

    public function firstHalf($user, array $params)
    {
        if ($params['period'] !== PeriodicName::FIRSTHALF) {
            return $this->error('', 'Invalid Period', 400);
        }

        $results = Result::with(['studentscore'])
            ->where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $params['student_id'],
                'period' => $params['period'],
                'term' => $params['term'],
                'session' => $params['session'],
                'result_type' => $params['result_type'],
            ])
            ->when(!empty($params['status']), function ($query) use ($params) {
                $query->where('status', $params['status']);
            })
            ->get();

        $getMidtermResults = MidTermResultResource::collection($results);

        $data = [
            'students' => $this->getStudentsByClass($user, $params['class']),
            'subjects' => $this->getSubjects($user, $params['class']),
            'results' => $getMidtermResults,
        ];

        return $this->success($data, 'Mid term result');
    }

    public function secondHalf($user, array $params)
    {
        if ($params['period'] !== PeriodicName::SECONDHALF) {
            return $this->error('', 'Invalid Period', 400);
        }

        $results = Result::with([
            'student',
            'studentscore',
            'affectivedisposition',
            'psychomotorskill',
            'resultextracurricular',
            'abacus',
            'psychomotorperformance',
            'pupilreport',
        ])
            ->where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $params['student_id'],
                'period' => $params['period'],
                'term' => $params['term'],
                'session' => $params['session'],
                'result_type' => $params['result_type'],
            ])
            ->when(!empty($params['status']), function ($query) use ($params) {
                $query->where('status', $params['status']);
            })
            ->get();

        $getEndTermResults = ResultResource::collection($results);

        $data = [
            'students' => $this->getStudentsByClass($user, $params['class']),
            'subjects' => $this->getSubjects($user, $params['class']),
            'grading' => $this->getGrading($user),
            'extra_curricular' => $this->getExtraCurricular($user),
            'results' => $getEndTermResults,
        ];

        return $this->success($data, 'Retrieved successfully');
    }

}
