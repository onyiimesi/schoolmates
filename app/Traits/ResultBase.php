<?php

namespace App\Traits;

use App\Models\AffectiveDisposition;
use App\Models\PsychomotorPerformance;
use App\Models\PsychomotorSkill;
use App\Models\PupilReport;
use App\Models\Result;
use App\Models\ResultExtraCurricular;
use App\Models\StudentScore;

trait ResultBase
{
    protected function getResult($teacher, $request)
    {
        return Result::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'First Half')
            ->where("result_type", $request->result_type)
            ->where("term", $request->term)
            ->where("session", $request->session)
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
            'status' => 'not-released'
        ]);

        $this->saveStudentScores($compute, $request->results);

        return [
            "status" => 'true',
            "message" => 'Computed Successfully',
        ];
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
            'status' => 'not-released'
        ]);

        $getResult->studentscore()->delete();
        $this->saveStudentScores($getResult, $request->results);

        return [
            "status" => 'true',
            "message" => 'Result Updated Successfully'
        ];
    }

    protected function saveStudentScores($result, $scores)
    {
        foreach ($scores as $score) {
            $question = new StudentScore($score);
            $result->studentscore()->save($question);
        }
    }

    protected function validateRequest($request)
    {
        $request->validated($request->all());
    }

    protected function getSecondResult($request, $teacher)
    {
        return Result::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'Second Half')
            ->where("term", $request->term)
            ->where("session", $request->session)->first();
    }

    protected function handleNewResult($request, $teacher, $hosId)
    {
        if ($teacher->teacher_type === "subject teacher") {
            $compute = Result::createOne($teacher, $request, $hosId);
            $this->saveStudentScoresTwo($compute, $request->results);
        }

        if ($teacher->teacher_type === "class teacher") {
            $compute = Result::createOne($teacher, $request, $hosId);
            $this->saveClassTeacherData($compute, $request, $teacher);
        }

        return [
            "status" => 'true',
            "message" => 'Computed Successfully',
        ];
    }

    protected function handleExistingResult($request, $teacher, $hosId, $getsecondresult)
    {
        if ($teacher->teacher_type === "subject teacher") {
            $this->updateEndTermResult($getsecondresult, $request);
            $this->saveStudentScores($getsecondresult, $request->results);
        }

        if ($teacher->teacher_type === "class teacher") {
            $this->updateEndTermResult($getsecondresult, $request, [
                'school_opened' => $request->school_opened,
                'times_present' => $request->times_present,
                'times_absent' => $request->school_opened - $request->times_present,
                'teacher_comment' => $request->teacher_comment,
                'performance_remark' => $request->performance_remark,
                'teacher_id' => $request->teacher_id,
                'teacher_fullname' => $teacher->surname . ' ' . $teacher->firstname,
                'hos_comment' => $request->hos_comment,
                'hos_id' => $request->hos_id,
                'hos_fullname' => $hosId->surname . ' ' . $hosId->firstname,
                'computed_endterm' => 'true',
                'status' => 'not-released'
            ]);
            $this->saveClassTeacherData($getsecondresult, $request, $teacher);
        }

        return [
            "status" => 'true',
            "message" => 'Updated Successfully'
        ];
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
            'status' => 'not-released'
        ], $additionalFields);

        $result->update($fields);
    }

    protected function saveStudentScoresTwo($compute, $results)
    {
        $compute->studentscore()->delete();
        foreach ($results as $result) {
            $question = new StudentScore($result);
            $compute->studentscore()->save($question);
        }
    }

    protected function saveClassTeacherData($compute, $request, $teacher)
    {
        $this->saveStudentScores($compute, $request->results);
        $this->saveAffectiveDispositions($compute, $request->affective_disposition);
        $this->savePsychomotorSkills($compute, $request->psychomotor_skills);
        $this->saveExtraCurricularActivities($compute, $request->extra_curricular_activities);

        if ($teacher->campus_type === "Elementary") {
            $this->saveAbacus($compute, $request->abacus);
        }

        $this->savePsychomotorPerformances($compute, $request->psychomotor_performance);
        $this->savePupilReports($compute, $request->pupil_report);
    }

    protected function saveAffectiveDispositions($compute, $affectiveDispositions)
    {
        $compute->affectivedisposition()->delete();
        foreach ($affectiveDispositions as $disposition) {
            $affective = new AffectiveDisposition($disposition);
            $compute->affectivedisposition()->save($affective);
        }
    }

    protected function savePsychomotorSkills($compute, $psychomotorSkills)
    {
        $compute->psychomotorskill()->delete();
        foreach ($psychomotorSkills as $skills) {
            $psy = new PsychomotorSkill($skills);
            $compute->psychomotorskill()->save($psy);
        }
    }

    protected function saveExtraCurricularActivities($compute, $extraCurricularActivities)
    {
        $compute->resultextracurricular()->delete();
        foreach ($extraCurricularActivities as $extra) {
            $ext = new ResultExtraCurricular($extra);
            $compute->resultextracurricular()->save($ext);
        }
    }

    protected function saveAbacus($compute, $abacus)
    {
        $compute->abacus()->delete();
        $compute->abacus()->create([
            'name' => $abacus['name']
        ]);
    }

    protected function savePsychomotorPerformances($compute, $psychomotorPerformances)
    {
        $compute->psychomotorperformance()->delete();
        foreach ($psychomotorPerformances as $performance) {
            $psycho = new PsychomotorPerformance($performance);
            $compute->psychomotorperformance()->save($psycho);
        }
    }

    protected function savePupilReports($compute, $pupilReports)
    {
        $compute->pupilreport()->delete();
        foreach ($pupilReports as $report) {
            $ext = new PupilReport($report);
            $compute->pupilreport()->save($ext);
        }
    }
}
