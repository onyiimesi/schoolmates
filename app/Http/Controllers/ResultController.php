<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultRequest;
use App\Models\AffectiveDisposition;
use App\Models\PsychomotorSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Result;
use App\Models\Staff;
use App\Models\StudentScore;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResultRequest $request)
    {
        $request->validated($request->all());

        $teacher = Auth::user();

        if($request->period == 'First Half'){

            $getresult = Result::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'First Half')
            ->where("term", $request->term)
            ->where("session", $request->session)->first();

            if(empty($getresult)){

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
                    'computed_midterm' => 'true'
                ]);

                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $compute->studentScores()->save($question);
                }

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                ];

            }else if(!empty($getresult)){

                $getresult->update([
                    'student_id' => $request->student_id,
                    'student_fullname' => $request->student_fullname,
                    'admission_number' => $request->admission_number,
                    'class_name' => $request->class_name,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'computed_midterm' => 'true'
                ]);

                $getresult->studentScores()->delete();
                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $getresult->studentScores()->save($question);
                }

                return [
                    "status" => 'true',
                    "message" => 'Result Updated Successfully'
                ];

            }

        }else if($request->period == 'Second Half'){

            $getsecondresult = Result::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'Second Half')
            ->where("term", $request->term)
            ->where("session", $request->session)->first();


            $hosId = Staff::find($request->hos_id);

            if(empty($getsecondresult)){

                $compute = Result::create([
                    'sch_id' => $teacher->sch_id,
                    'campus' => $teacher->campus,
                    'campus_type' => $teacher->campus_type,
                    'student_id' => $request->student_id,
                    'student_fullname' => $request->student_fullname,
                    'admission_number' => $request->admission_number,
                    'class_name' => $request->class_name,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'school_opened' => $request->school_opened,
                    'times_present' => $request->times_present,
                    'times_absent' => $request->times_absent,
                    'teacher_comment' => $request->teacher_comment,
                    'teacher_id' => $request->teacher_id,
                    'teacher_fullname' => $teacher->surname . ' '. $teacher->firstname,
                    'hos_comment' => $request->hos_comment,
                    'hos_id' => $request->hos_id,
                    'hos_fullname' => $hosId->surname . ' '. $hosId->firstname,
                    'computed_endterm' => 'true',
                ]);

                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $compute->studentScores()->save($question);
                }

                $compute->affectiveDispositions()->createMany($request->affective_disposition);

                foreach ($request->psychomotor_skills as $skills) {
                    $psy = new PsychomotorSkill($skills);
                    $compute->psychomotorskill()->save($psy);
                }

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                ];

            }else if(!empty($getsecondresult)){

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
                    'times_absent' => $request->times_absent,
                    'teacher_comment' => $request->teacher_comment,
                    'teacher_id' => $request->teacher_id,
                    'teacher_fullname' => $teacher->surname . ' '. $teacher->firstname,
                    'hos_comment' => $request->hos_comment,
                    'hos_id' => $request->hos_id,
                    'hos_fullname' => $hosId->surname . ' '. $hosId->firstname,
                    'computed_endterm' => 'true'
                ]);


                $getsecondresult->studentScores()->delete();
                foreach ($request->results as $result) {
                    $question = new StudentScore($result);
                    $getsecondresult->studentScores()->save($question);
                }

                $getsecondresult->affectiveDispositions()->delete();
                foreach ($request->affective_disposition as $affective) {
                    $disposition = new AffectiveDisposition($affective);
                    $getsecondresult->affectiveDispositions()->save($disposition);
                }

                $getsecondresult->psychomotorskill()->delete();
                foreach ($request->psychomotor_skills as $skills) {
                    $psy = new PsychomotorSkill($skills);
                    $getsecondresult->psychomotorskill()->save($psy);
                }

                return [
                    "status" => 'true',
                    "message" => 'Updated Successfully'
                ];

            }
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
