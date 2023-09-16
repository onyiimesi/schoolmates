<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignClassRequest;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Staff;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class AssignClassController extends Controller
{

    use HttpResponses;

    public function assign(AssignClassRequest $request, Staff $staff){

        $staff = Staff::where('id', $request->id)->first();

        if(!$staff){
            return $this->error('', 'Staff does not exist', 400);
        }

        $teacher_firstname = $staff->firstname;
        $teacher_surname = $staff->surname;
        $teacher_middlename = $staff->middlename;

        $staff->update([
            'class_assigned' => $request->class_assigned,
            'sub_class' => $request->sub_class,
        ]);

        Student::where('present_class', $request->class_assigned)
        ->where('sub_class', $request->sub_class)
        ->update([
            'teacher_surname' => $teacher_firstname,
            'teacher_firstname' => $teacher_surname,
            'teacher_middlename' => $teacher_middlename,
        ]);

        return [
            "status" => 'true',
            "message" => 'Class Assigned Successfully',
        ];


    }
}
