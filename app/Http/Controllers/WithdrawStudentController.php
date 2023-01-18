<?php

namespace App\Http\Controllers;

use App\Http\Requests\WithdrawStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class WithdrawStudentController extends Controller
{
    public function withdraw(WithdrawStudentRequest $request, Student $student){
        
        $student = Student::where('id', $request->id)->first();

        if(!$student){
            return $this->error('', 'Student does not exist', 400);
        }

        $student->update([
            'status' => 'withdrawn',
        ]);

        return [
            "status" => 'true',
            "message" => 'Student Withdrawn Successfully',
        ];

        
    }
}
