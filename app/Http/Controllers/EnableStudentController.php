<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class EnableStudentController extends Controller
{
    use HttpResponses;

    public function enable($id){

        $student = Student::find($id);

        if(!$student){
            return $this->error('', 'Student does not exist', 400);
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
