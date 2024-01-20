<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class AcceptStudentController extends Controller
{
    public function accept(AcceptStudentRequest $request, Student $student){

        $student = Student::find($request->id);

        if(!$student){
            return $this->error('', 'Student does not exist', 400);
        }

        $student->update([
            'status' => 'active',
        ]);

        return [
            "status" => 'true',
            "message" => 'Student Accepted Successfully',
        ];
    }
}
