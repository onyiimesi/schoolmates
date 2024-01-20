<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignClassRequest;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\AcademicPeriod;
use App\Models\ClassModel;
use App\Models\Staff;
use App\Models\Student;
use App\Models\SubjectTeacher;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignClassController extends Controller
{

    use HttpResponses;

    public function assign(AssignClassRequest $request, Staff $staff){
        $user = Auth::user();

        $staff = Staff::where('id', $request->id)->first();
        $period = AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->first();
        $class = ClassModel::findorFail($request->class_id);

        if(!$staff){
            return $this->error('', 'Staff does not exist', 400);
        }

        if($staff->teacher_type == ""){
            return $this->error('', 'Teacher type is empty, update record', 400);
        }

        $teacher_firstname = $staff->firstname;
        $teacher_surname = $staff->surname;
        $teacher_middlename = $staff->middlename;

        $subT = SubjectTeacher::where('staff_id', $request->id)->first();
        
        if($staff->teacher_type == "subject teacher"){

            $staff->update([
                'class_assigned' => $request->class_assigned,
            ]);

            Student::where('sch_id', $staff->sch_id)
            ->where('campus', $staff->campus)
            ->where('present_class', $request->class_assigned)
            ->update([
                'teacher_surname' => $teacher_firstname,
                'teacher_firstname' => $teacher_surname,
                'teacher_middlename' => $teacher_middlename,
            ]);
            
            if(empty($subT)){

                SubjectTeacher::create([
                    'sch_id' => $staff->sch_id,
                    'campus' => $staff->campus,
                    'term' => $period->term,
                    'session' => $period->session,
                    'class_id' => $request->class_id,
                    'staff_id' => $request->id,
                    'class_name' => $class->class_name,
                    'subject' => $request->subjects
                ]);

            }else {
                $subT->update([
                    'class_id' => $request->class_id,
                    'class_name' => $class->class_name,
                    'subject' => $request->subjects
                ]);
            }

            return [
                "status" => 'true',
                "message" => 'Assigned Successfully'
            ];
        }

        $staff->update([
            'class_assigned' => $request->class_assigned,
        ]);

        Student::where('sch_id', $staff->sch_id)
        ->where('campus', $staff->campus)
        ->where('present_class', $request->class_assigned)
        ->update([
            'teacher_surname' => $teacher_firstname,
            'teacher_firstname' => $teacher_surname,
            'teacher_middlename' => $teacher_middlename,
        ]);

        if(empty($subT)){

            SubjectTeacher::create([
                'sch_id' => $staff->sch_id,
                'campus' => $staff->campus,
                'term' => $period->term,
                'session' => $period->session,
                'class_id' => $request->class_id,
                'staff_id' => $request->id,
                'class_name' => $class->class_name,
                'subject' => $request->subjects
            ]);

        }else {
            $subT->update([
                'class_id' => $request->class_id,
                'class_name' => $class->class_name,
                'subject' => $request->subjects
            ]);
        }

        return [
            "status" => 'true',
            "message" => 'Assigned Successfully'
        ];
    }
}
