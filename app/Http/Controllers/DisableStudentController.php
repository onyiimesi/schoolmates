<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class DisableStudentController extends Controller
{
    use HttpResponses;

    public function disable($id){

        $student = Student::find($id);

        if(!$student){
            return $this->error('', 'Student does not exist', 400);
        }

        $student->update([
            'status' => 'disabled',
        ]);

        return [
            "status" => 'true',
            "message" => 'Account Disabled Successfully',
        ];
    }
}
