<?php

namespace App\Services;

use App\Models\AcademicPeriod;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\SubjectTeacher;
use App\Models\BusRouting;
use App\Http\Resources\BusRoutingResource;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GeneralService
{
    use HttpResponses;

    public function assign($request, $staff): JsonResponse
    {
        $user = Auth::user();

        $period = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->first();

        if (! $period) {
            return $this->error(null, 'Academic period not found', 400);
        }

        $class = ClassModel::findOrFail($request->class_id);

        if ($staff->teacher_type === null) {
            return $this->error(null, 'Teacher type is empty, update record', 400);
        }

        $staff->update([
            'class_assigned' => $request->class_assigned,
        ]);

        $teacher_firstname = $staff->firstname;
        $teacher_surname = $staff->surname;
        $teacher_middlename = $staff->middlename;

        Student::where('sch_id', $staff->sch_id)
            ->where('campus', $staff->campus)
            ->where('present_class', $request->class_assigned)
            ->update([
                'teacher_surname' => $teacher_firstname,
                'teacher_firstname' => $teacher_surname,
                'teacher_middlename' => $teacher_middlename,
            ]);

        $data = [
            'sch_id' => $staff->sch_id,
            'campus' => $staff->campus,
            'term' => $period->term,
            'session' => $period->session,
            'class_id' => $request->class_id,
            'staff_id' => $staff->id,
            'class_name'=> $class->class_name,
            'subject' => $request->subjects,
        ];

        if($staff->teacher_type == "subject teacher") {

            $subjectTeacher = SubjectTeacher::where('staff_id', $staff->id)->first();

            if ($subjectTeacher) {
                $subjectTeacher->update($data);
            } else {
                SubjectTeacher::create($data);
            }
        }

        if ($subjectTeacher) {
            $subjectTeacher->update($data);
        } else {
            SubjectTeacher::create($data);
        }

        return $this->success(null, "Assigned Successfully");
    }

    public function getVehicle(): JsonResponse
    {
        $user = Auth::user();
        $student = Student::findOrFail($user->id);

        $data = BusRoutingResource::collection(
            BusRouting::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('student_id', $student->id)
            ->get()
        );

        return $this->success($data, "Your Assigned Bus");
    }

    public function getVehicles(): JsonResponse
    {
        $user = Auth::user();

        $data = BusRoutingResource::collection(
            BusRouting::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return $this->success($data, "Assigned Bus");
    }
}
