<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultRequest;
use App\Http\Resources\ResultResource;
use App\Models\GradingSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Result;
use App\Models\Staff;
use stdClass;

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
                    'student_fullname' => $request->student_fullname,
                    'admission_number' => $request->admission_number,
                    'class_name' => $request->class_name,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'results' => $request->results,
                    'computed_midterm' => 'true',
                ]);

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                    "data" => $compute
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
                    'results' => $request->results,
                    'computed_midterm' => 'true',
                ]);

                return [
                    "status" => 'true',
                    "message" => 'Result Updated Successfully',
                    "data" => $getresult
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
                    'results' => $request->results,
                    'affective_disposition' => $request->affective_disposition,
                    'psychomotor_skills' => $request->psychomotor_skills,
                    'teacher_comment' => $request->teacher_comment,
                    'teacher_id' => $request->teacher_id,
                    'teacher_fullname' => $teacher->surname . ' '. $teacher->firstname,
                    'hos_comment' => $request->hos_comment,
                    'hos_id' => $request->hos_id,
                    'hos_fullname' => $hosId->surname . ' '. $hosId->firstname,
                    'computed_endterm' => 'true',
                ]);

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                    "data" => $compute
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
                    'results' => $request->results,
                    'affective_disposition' => $request->affective_disposition,
                    'psychomotor_skills' => $request->psychomotor_skills,
                    'teacher_comment' => $request->teacher_comment,
                    'teacher_id' => $request->teacher_id,
                    'teacher_fullname' => $teacher->surname . ' '. $teacher->firstname,
                    'hos_comment' => $request->hos_comment,
                    'hos_id' => $request->hos_id,
                    'hos_fullname' => $hosId->surname . ' '. $hosId->firstname,
                    'computed_endterm' => 'true',
                ]);

                return [
                    "status" => 'true',
                    "message" => 'Updated Successfully',
                    "data" => $getsecondresult
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
