<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentAttendanceResource;
use App\Http\Resources\StudentResource;
use App\Models\AcademicPeriod;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceDateController extends Controller
{
    public function attendancedate(Request $request){

        $user = Auth::user();

        if($request->date){
            $search = StudentAttendance::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where("attendance_date", $request->date)
            ->get();

            $s = StudentAttendanceResource::collection($search);

            return [
                'status' => 'true',
                'message' => '',
                'data' => $s
            ];
        }

    }
}
