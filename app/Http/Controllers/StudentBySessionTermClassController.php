<?php

namespace App\Http\Controllers;

use App\Enum\PeriodicName;
use App\Enum\StudentStatus;
use App\Http\Resources\StudentResource;
use App\Models\AcademicSessions;
use App\Models\Result;
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
            ->whereClass("present_class", $request->class)
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
            ->whereClass("present_class", $request->present_class)
            ->where('status', StudentStatus::ACTIVE)
            ->get();

        // A class roster is session-based. Students who were in this class
        // during the current session but have since been promoted still have
        // result rows for it, so the broadsheet includes them. Add them here
        // too, otherwise the UI shows a different set of names from the
        // broadsheet. Current-class students stay first so existing callers
        // that rely on the first row keep their current behaviour.
        $currentSession = currentAcademicSession($user);

        if ($currentSession) {
            $resultStudentIds = Result::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->whereClass('class_name', $request->present_class)
                ->where('session', $currentSession)
                ->whereIn('period', [
                    PeriodicName::FIRSTHALF,
                    PeriodicName::SECONDHALF,
                ])
                ->whereNotIn('student_id', $searchStud->pluck('id')->all())
                ->distinct()
                ->pluck('student_id');

            if ($resultStudentIds->isNotEmpty()) {
                $historicalStudents = Student::where('sch_id', $user->sch_id)
                    ->where('campus', $user->campus)
                    ->whereIn('id', $resultStudentIds)
                    ->where('status', StudentStatus::ACTIVE)
                    ->get();

                $searchStud = $searchStud->concat($historicalStudents);
            }
        }

        $data = StudentResource::collection($searchStud);

        return $this->success($data, "Student list");
    }
}
