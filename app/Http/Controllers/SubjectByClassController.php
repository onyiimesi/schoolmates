<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentExcelImportResource;
use App\Http\Resources\SubjectResource;
use App\Models\StudentExcelImport;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectByClassController extends Controller
{
    public function subjectbyclass(Request $request){

        $user = Auth::user();

        $Subject = SubjectResource::collection(
            Subject::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $request->class)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Subjects',
            'data' => $Subject
        ];

    }

    public function studentExcelImport(){

        $Subject = StudentExcelImportResource::collection(StudentExcelImport::get());

        return [
            'status' => 'true',
            'message' => '',
            'data' => $Subject
        ];

    }
}
