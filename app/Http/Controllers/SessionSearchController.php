<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class SessionSearchController extends Controller
{
    use HttpResponses;

    public function sessionsearch(Request $request){

        $search = Student::where("session_admitted", $request->session)->get();

        $s = StudentResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }
}
