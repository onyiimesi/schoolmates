<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

        $gra = StudentResource::collection(
            Student::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('status', 'graduated')
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Graduated Students List',
            'data' => $gra
        ];
    }
}
