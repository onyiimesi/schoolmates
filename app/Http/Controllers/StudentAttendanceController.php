<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentAttendanceRequest;
use App\Http\Resources\StudentAttendanceResource;
use App\Models\AcademicPeriod;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $studentatt = StudentAttendanceResource::collection(
            StudentAttendance::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Attendance List',
            'data' => $studentatt
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentAttendanceRequest $request)
    {
        $request->validated($request->all());

        $teacher = Auth::user();

        $period = AcademicPeriod::where('sch_id', $teacher->sch_id)
        ->first();

        $search = StudentAttendance::where("attendance_date", $request->attendance_date)->first();


        if(empty($search)){

            $studentatt = StudentAttendance::create([
                'sch_id' => '1234',
                // 'student_id' => $request->student_id,
                // 'admission_number' => $request->admission_number,
                // 'student_fullname' => $request->student_fullname,
                'attendance_date' => $request->attendance_date,
                'data' => $request->data,
                'class' => $teacher->class_assigned .' '. $teacher->sub_class,
                'period' => $period->period,
                'term' => $period->term,
                'session' => $period->session,

                // 'status' => $status,
            ]);


            return [
                "status" => 'true',
                "message" => 'Attendance Created Successfully',
                "data" => $studentatt
            ];

        }else if(!empty($search)){

            $search->update([
                'sch_id' => '1234',
                // 'student_id' => $request->student_id,
                // 'admission_number' => $request->admission_number,
                // 'student_fullname' => $request->student_fullname,
                'attendance_date' => $request->attendance_date,
                'data' => $request->data,
                'class' => $teacher->class_assigned .' '. $teacher->sub_class,
                'period' => $period->period,
                'term' => $period->term,
                'session' => $period->session,

                // 'status' => $status,
            ]);


            return [
                "status" => 'true',
                "message" => 'Attendance Updated Successfully',
                "data" => $search
            ];

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
        //
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
