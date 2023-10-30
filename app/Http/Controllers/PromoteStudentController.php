<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoteStudentRequest;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class PromoteStudentController extends Controller
{
    use HttpResponses;

    public function promote(PromoteStudentRequest $request, Student $student)
    {

        $student = Student::find($request->id);

        if(!$student){
            return $this->error('', 'Student does not exist', 400);
        }

        $student->update([
            'present_class' => $request->present_class,
            'sub_class' => $request->sub_class,

        ]);

        return [
            "status" => 'true',
            "message" => 'Student Has Been Promoted',
        ];

    }

    public function promotestudents(Request $request)
    {
        $selectedClass = $request->class;
        $checkedStudents = $request->students;

        Student::whereIn('id', $checkedStudents)
        ->update([
            'present_class' => $selectedClass
        ]);

        return [
            "status" => 'true',
            "message" => 'Students Has Been Promoted',
        ];
    }
}
