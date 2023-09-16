<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentBySessionTermClassController extends Controller
{
    public function studentsessionclassterm(Request $request){

        $user = Auth::user();

        $search = Student::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("session_admitted", $request->session)
        ->where("present_class", $request->class)
        ->all();

        $s = StudentResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }

    // Student By Class (Principal)
    public function studentbyclass(Request $request){

        $user = Auth::user();

        $searchStud = Student::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("present_class", $request->present_class)
        ->get();

        $se = StudentResource::collection($searchStud);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $se
        ];

    }
}
