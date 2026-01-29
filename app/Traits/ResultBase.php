<?php

namespace App\Traits;

use App\Enum\PeriodicName;
use App\Enum\ResultStatus;
use App\Models\Result;
use App\Services\Cache\MemoizedCacheService;

trait ResultBase
{
    use HttpResponses;

    protected function getResult($teacher, $request)
    {
        return Result::where([
                'sch_id' => $teacher->sch_id,
                'campus' => $teacher->campus,
                'student_id' => $request->student_id,
                'period' => PeriodicName::FIRSTHALF,
                'term' => $request->term,
                'session' => $request->session,
            ])
            ->first();
    }

    protected function createResult($teacher, $request)
    {
        $compute = Result::create([
            'sch_id' => $teacher->sch_id,
            'campus' => $teacher->campus,
            'campus_type' => $teacher->campus_type,
            'student_id' => $request->student_id,
            'teacher_id' => $teacher->id,
            'student_fullname' => $request->student_fullname,
            'admission_number' => $request->admission_number,
            'class_name' => $request->class_name,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->session,
            'computed_midterm' => 'true',
            'result_type' => $request->result_type,
            'teacher_comment' => $request->teacher_comment,
            'status' => ResultStatus::NOTRELEASED->value,
        ]);

        $this->saveStudentScores($compute, $request->results);

        return $this->success(null, 'Computed Successfully', 201);
    }

    protected function updateResult($getResult, $request)
    {
        $getResult->update([
            'student_id' => $request->student_id,
            'student_fullname' => $request->student_fullname,
            'admission_number' => $request->admission_number,
            'class_name' => $request->class_name,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->session,
            'computed_midterm' => 'true',
            'result_type' => $request->result_type,
            'teacher_comment' => $request->teacher_comment,
            'status' => ResultStatus::NOTRELEASED->value,
        ]);

        $this->saveStudentScores($getResult, $request->results);

        return $this->success(null, 'Updated Successfully');
    }

    protected function validateRequest($request)
    {
        $request->validated($request->all());
    }

    protected function getSecondResult($request, $teacher)
    {
        return Result::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where('student_id', $request->student_id)
            ->where('period', PeriodicName::SECONDHALF)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->first();
    }

    protected function handleNewResult($request, $teacher, $hos)
    {
        if ($teacher->teacher_type === "subject teacher") {
            $result = Result::createOne($teacher, $request, $hos);
            $this->saveStudentScores($result, $request->results);
        }

        if ($teacher->teacher_type === "class teacher") {
            $compute = Result::createOne($teacher, $request, $hos);
            $this->saveClassTeacherData($compute, $request);
        }

        return $this->success(null, 'Computed Successfully', 201);
    }

    protected function saveStudentScores($result, $scores)
    {
        $result->studentScores()->delete();
        $result->studentScores()->createMany($scores);
    }

    protected function handleExistingResult($request, $teacher, $hosId, $getsecondresult)
    {
        if ($teacher->teacher_type === "subject teacher") {
            $this->updateEndTermResult($getsecondresult, $request);
            $this->saveStudentScores($getsecondresult, $request->results);
        }

        if ($teacher->teacher_type === "class teacher") {
            $hosFullName = $hosId ? "{$hosId->surname} {$hosId->firstname}" : null;

            $this->updateEndTermResult($getsecondresult, $request, [
                'school_opened' => $request->school_opened,
                'times_present' => $request->times_present,
                'times_absent' => $request->school_opened - $request->times_present,
                'teacher_comment' => $request->teacher_comment,
                'performance_remark' => $request->performance_remark,
                'teacher_id' => $request->teacher_id,
                'teacher_fullname' => "{$teacher->surname} {$teacher->firstname}",
                'hos_comment' => $request->hos_comment,
                'hos_id' => $request->hos_id,
                'hos_fullname' => $hosFullName,
                'computed_endterm' => 'true',
                'status' => ResultStatus::NOTRELEASED->value,
            ]);
            $this->saveStudentScores($getsecondresult, $request->results);
            $this->saveClassTeacherData($getsecondresult, $request);
        }

        return $this->success(null, 'Updated Successfully');
    }

    protected function updateEndTermResult($result, $request, $additionalFields = [])
    {
        $fields = array_merge([
            'student_id' => $request->student_id,
            'student_fullname' => $request->student_fullname,
            'admission_number' => $request->admission_number,
            'class_name' => $request->class_name,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->session,
            'status' => ResultStatus::NOTRELEASED->value,
        ], $additionalFields);

        $result->update($fields);
    }

    protected function saveClassTeacherData($result, $request)
    {
        $this->saveStudentScores($result, $request->results);

        if ($request->filled('affective_disposition')) {
            $this->saveAffectiveDispositions($result, $request->affective_disposition);
        }

        if ($request->filled('psychomotor_skills')) {
            $this->savePsychomotorSkills($result, $request->psychomotor_skills);
        }

        if ($request->filled('extra_curricular_activities')) {
            $this->saveExtraCurricularActivities($result, $request->extra_curricular_activities);
        }

        if (!empty($request->abacus['name'])) {
            $this->saveAbacus($result, $request->abacus);
        }

        if ($request->filled('psychomotor_performance')) {
            $this->savePsychomotorPerformances($result, $request->psychomotor_performance);
        }

        if ($request->filled('pupil_report')) {
            $this->savePupilReports($result, $request->pupil_report);
        }
    }

    protected function saveAffectiveDispositions($result, $affectiveDispositions)
    {
        $result->affectiveDispositions()->delete();
        $result->affectiveDispositions()->createMany($affectiveDispositions);
    }

    protected function savePsychomotorSkills($result, $psychomotorSkills)
    {
        $result->psychomotorskill()->delete();
        $result->psychomotorskill()->createMany($psychomotorSkills);
    }

    protected function saveExtraCurricularActivities($result, $extraCurricularActivities)
    {
        $result->resultExtraCurriculars()->delete();
        $result->resultExtraCurriculars()->createMany($extraCurricularActivities);
    }

    protected function saveAbacus($result, $abacus)
    {
        $result->abacus()->delete();
        $result->abacus()->create([
            'name' => $abacus['name']
        ]);
    }

    protected function savePsychomotorPerformances($result, $psychomotorPerformances)
    {
        $result->psychomotorPerformances()->delete();
        $result->psychomotorPerformances()->createMany($psychomotorPerformances);
    }

    protected function savePupilReports($result, $pupilReports)
    {
        $result->pupilReports()->delete();
        $result->pupilReports()->createMany($pupilReports);
    }

    protected function getStudentResults($user, array $params, MemoizedCacheService $generalResultService)
    {
        return match ($params['period']) {
            PeriodicName::FIRSTHALF => $generalResultService->firstHalf($user, $params),
            PeriodicName::SECONDHALF => $generalResultService->secondHalf($user, $params),
            default => $this->error(null, 'Invalid result type', 400),
        };
    }
}
