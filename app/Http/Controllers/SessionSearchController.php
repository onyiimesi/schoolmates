<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class SessionSearchController extends Controller
{
    use HttpResponses;

    public function sessionsearch(Request $request){
        $user = Auth::user();

        $search = Student::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("session_admitted", $request->session)
        ->get();

        $s = StudentResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }
}
