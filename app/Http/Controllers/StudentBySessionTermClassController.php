<?php

namespace App\Http\Controllers;

use App\Enum\StudentStatus;
use App\Http\Resources\StudentResource;
use App\Models\AcademicSessions;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentBySessionTermClassController extends Controller
{
    use HttpResponses;

    public function studentsessionclassterm(Request $request){

        $user = Auth::user();

        $session = AcademicSessions::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('academic_session', $request->session)
            ->first();

        if(!$session){
            return $this->error(null, "Academic session does not match", 400);
        }

        $search = Student::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where("present_class", $request->class)
            ->where('status', StudentStatus::ACTIVE)
            ->get();

        $data = StudentResource::collection($search);

        return $this->success($data, "Student list");
    }

    // Student By Class (Principal)
    public function studentbyclass(Request $request)
    {
        $user = Auth::user();

        $searchStud = Student::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where("present_class", $request->present_class)
            ->where('status', StudentStatus::ACTIVE)
            ->get();

        $data = StudentResource::collection($searchStud);

        return $this->success($data, "Student list");
    }
}
