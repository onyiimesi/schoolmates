<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class TransferStudentController extends Controller
{
    use HttpResponses;

    public function transfer(Request $request, Student $student){

        $student = Student::find($request->id);

        if(!$student){
            return $this->error('', 'Student does not exist', 400);
        }

        $student->update([
            'campus' => $request->campus,
            'present_class' => $request->present_class,
            'sub_class' => $request->sub_class
        ]);

        return [
            "status" => "true",
            "message" => "Student Transferred Successfully",
        ];
    }
}
