<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class GraduatedStudentController extends Controller
{
    public function graduate(){
        
        $gra = StudentResource::collection(Student::where('status', 'graduated')->get());

        return [
            'status' => 'true',
            'message' => 'Graduated Students List',
            'data' => $gra
        ];

        
    }
}
