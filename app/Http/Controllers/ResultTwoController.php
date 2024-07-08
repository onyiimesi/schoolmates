<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultRequest;
use App\Models\AffectiveDisposition;
use App\Models\PsychomotorPerformance;
use App\Models\PsychomotorSkill;
use App\Models\PupilReport;
use App\Models\Result;
use App\Models\ResultExtraCurricular;
use App\Models\Staff;
use App\Models\StudentScore;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultTwoController extends Controller
{

    use HttpResponses;

    public function mid(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'string',],
            'student_fullname' => ['required', 'string',],
            'admission_number' => ['required', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
            'term' => ['required', 'string', 'max:255'],
            'session' => ['required', 'string', 'max:255'],
            'result_type' => 'required|in:first_assesment,second_assesment,midterm'
        ]);

        $teacher = Auth::user();

        if($request->period === "First Half" && $request->result_type === "first_assesment"){

            $getResult = Result::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'First Half')
            ->where("result_type", 'first_assesment')
            ->where("term", $request->term)
            ->where("session", $request->session)->first();

            if(empty($getResult)){

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
                    'teacher_comment' => $request->teacher_comment
                ]);

                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $compute->studentscore()->save($question);
                }

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                ];

            }elseif(!empty($getResult)){

                $getResult->update([
                    'student_id' => $request->student_id,
                    'student_fullname' => $request->student_fullname,
                    'admission_number' => $request->admission_number,
                    'class_name' => $request->class_name,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'computed_midterm' => 'true',
                    'teacher_comment' => $request->teacher_comment
                ]);

                $getResult->studentscore()->delete();
                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $getResult->studentscore()->save($question);
                }

                return [
                    "status" => 'true',
                    "message" => 'Result Updated Successfully'
                ];
            }
        }

        if($request->period === "First Half" && $request->result_type === "second_assesment"){

            $getResult = Result::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'First Half')
            ->where("result_type", 'second_assesment')
            ->where("term", $request->term)
            ->where("session", $request->session)->first();

            if(empty($getResult)){

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
                    'teacher_comment' => $request->teacher_comment
                ]);

                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $compute->studentscore()->save($question);
                }

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                ];

            }else if(!empty($getResult)){

                $getResult->update([
                    'student_id' => $request->student_id,
                    'student_fullname' => $request->student_fullname,
                    'admission_number' => $request->admission_number,
                    'class_name' => $request->class_name,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'computed_midterm' => 'true',
                    'teacher_comment' => $request->teacher_comment
                ]);

                $getResult->studentscore()->delete();
                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $getResult->studentscore()->save($question);
                }

                return [
                    "status" => 'true',
                    "message" => 'Result Updated Successfully'
                ];

            }

        }

        if($request->period === "First Half" && $request->result_type === "midterm"){

            $getResult = Result::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'First Half')
            ->where("result_type", 'midterm')
            ->where("term", $request->term)
            ->where("session", $request->session)->first();

            if(empty($getResult)){

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
                    'teacher_comment' => $request->teacher_comment
                ]);

                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $compute->studentscore()->save($question);
                }

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                ];

            }elseif(!empty($getResult)){

                $getResult->update([
                    'student_id' => $request->student_id,
                    'student_fullname' => $request->student_fullname,
                    'admission_number' => $request->admission_number,
                    'class_name' => $request->class_name,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'computed_midterm' => 'true',
                    'teacher_comment' => $request->teacher_comment
                ]);

                $getResult->studentscore()->delete();
                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $getResult->studentscore()->save($question);
                }

                return [
                    "status" => 'true',
                    "message" => 'Result Updated Successfully'
                ];

            }

        }

        return $this->error('', 'Bad Request', 400);
    }

    public function endTerm(ResultRequest $request)
    {
        $request->validated($request->all());
        $teacher = Auth::user();

        if($request->period === "Second Half"){

            $getsecondresult = Result::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'Second Half')
            ->where("term", $request->term)
            ->where("session", $request->session)->first();

            $hosId = Staff::find($request->hos_id);
            if(!$hosId){
                return $this->error(null, "Hod needs to add comments", 400);
            }

            if(empty($getsecondresult)){

                if($teacher->teacher_type === "subject teacher")
                {
                    $compute = Result::createOne($teacher, $request, $hosId);
                    foreach ($request->results as $result) {
                        $question = new StudentScore($result);
                        $compute->studentscore()->save($question);
                    }
                }

                if($teacher->teacher_type === "class teacher")
                {
                    $compute = Result::createOne($teacher, $request, $hosId);

                    foreach ($request->results as $result) {
                        $question = new StudentScore($result);
                        $compute->studentscore()->save($question);
                    }

                    foreach ($request->affective_disposition as $affective) {
                        $disposition = new AffectiveDisposition($affective);
                        $compute->affectivedisposition()->save($disposition);
                    }

                    foreach ($request->psychomotor_skills as $skills) {
                        $psy = new PsychomotorSkill($skills);
                        $compute->psychomotorskill()->save($psy);
                    }

                    foreach ($request->extra_curricular_activities as $extra) {
                        $ext = new ResultExtraCurricular($extra);
                        $compute->resultextracurricular()->save($ext);
                    }

                    if($teacher->campus_type === "Elementary"){
                        $compute->abacus()->create([
                            'name' => $request->abacus['name']
                        ]);
                    }

                    foreach ($request->psychomotor_performance as $psycho) {
                        $ext = new PsychomotorPerformance($psycho);
                        $compute->psychomotorperformance()->save($ext);
                    }

                    foreach ($request->pupil_report as $extra) {
                        $ext = new PupilReport($extra);
                        $compute->pupilreport()->save($ext);
                    }
                }

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                ];

            }elseif(!empty($getsecondresult)){

                if($teacher->teacher_type === "subject teacher")
                {
                    $getsecondresult->update([
                        'student_id' => $request->student_id,
                        'student_fullname' => $request->student_fullname,
                        'admission_number' => $request->admission_number,
                        'class_name' => $request->class_name,
                        'period' => $request->period,
                        'term' => $request->term,
                        'session' => $request->session,
                    ]);

                    $getsecondresult->studentscore()->delete();
                    foreach ($request->results as $result) {
                        $question = new StudentScore($result);
                        $getsecondresult->studentscore()->save($question);
                    }
                }

                if($teacher->teacher_type === "class teacher")
                {
                    $getsecondresult->update([
                        'student_id' => $request->student_id,
                        'student_fullname' => $request->student_fullname,
                        'admission_number' => $request->admission_number,
                        'class_name' => $request->class_name,
                        'period' => $request->period,
                        'term' => $request->term,
                        'session' => $request->session,
                        'school_opened' => $request->school_opened,
                        'times_present' => $request->times_present,
                        'times_absent' => $request->school_opened - $request->times_present,
                        'teacher_comment' => $request->teacher_comment,
                        'performance_remark' => $request->performance_remark,
                        'teacher_id' => $request->teacher_id,
                        'teacher_fullname' => $teacher->surname . ' '. $teacher->firstname,
                        'hos_comment' => $request->hos_comment,
                        'hos_id' => $request->hos_id,
                        'hos_fullname' => $hosId->surname . ' '. $hosId->firstname,
                        'computed_endterm' => 'true'
                    ]);

                    $getsecondresult->studentscore()->delete();
                    foreach ($request->results as $result) {
                        $question = new StudentScore($result);
                        $getsecondresult->studentscore()->save($question);
                    }

                    $getsecondresult->affectivedisposition()->delete();
                    foreach ($request->affective_disposition as $affective) {
                        $disposition = new AffectiveDisposition($affective);
                        $getsecondresult->affectivedisposition()->save($disposition);
                    }

                    $getsecondresult->psychomotorskill()->delete();
                    foreach ($request->psychomotor_skills as $skills) {
                        $psy = new PsychomotorSkill($skills);
                        $getsecondresult->psychomotorskill()->save($psy);
                    }

                    $getsecondresult->resultextracurricular()->delete();
                    foreach ($request->extra_curricular_activities as $extra) {
                        $ext = new ResultExtraCurricular($extra);
                        $getsecondresult->resultextracurricular()->save($ext);
                    }

                    if($teacher->campus_type == "Elementary"){
                        $getsecondresult->abacus()->delete();
                        $getsecondresult->abacus()->create([
                            'name' => $request->abacus['name']
                        ]);
                    }

                    $getsecondresult->psychomotorperformance()->delete();
                    foreach ($request->psychomotor_performance as $psycho) {
                        $ext = new PsychomotorPerformance($psycho);
                        $getsecondresult->psychomotorperformance()->save($ext);
                    }

                    $getsecondresult->pupilreport()->delete();
                    foreach ($request->pupil_report as $extra) {
                        $ext = new PupilReport($extra);
                        $getsecondresult->pupilreport()->save($ext);
                    }
                }

                return [
                    "status" => 'true',
                    "message" => 'Updated Successfully'
                ];
            }
        }

        return $this->error('', 'Bad Request', 400);
    }
}
