<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnableStudentRequest;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class EnableStudentController extends Controller
{
    use HttpResponses;

    public function enable(EnableStudentRequest $request, Student $student){

        $student = Student::find($request->id);

        if(!$student){
            return $this->error('', 'Staff does not exist', 400);
        }

        $student->update([
            'status' => 'active',
        ]);

        return [
            "status" => 'true',
            "message" => 'Account Enabled Successfully',
        ];


    }
}
