<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class AdmissionNumSearchController extends Controller
{
    public function admissionsearch(Request $request){

        $search = Student::where("admission_number", $request->admissionnumber)->get();

        $s = StudentResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }
}
