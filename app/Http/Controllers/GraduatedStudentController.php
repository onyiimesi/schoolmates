<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class GraduatedStudentController extends Controller
{

    public function graduatestudent(Request $request, Student $student){

        $student = Student::find($request->id);

        if(!$student){
            return $this->error('', 'Student does not exist', 400);
        }

        $student->update([
            'status' => 'graduated'
        ]);

        return [
            "status" => 'true',
            "message" => 'Graduated',
        ];

    }

    public function graduate(){

        $gra = StudentResource::collection(Student::where('status', 'graduated')->get());

        return [
            'status' => 'true',
            'message' => 'Graduated Students List',
            'data' => $gra
        ];

    }
}
