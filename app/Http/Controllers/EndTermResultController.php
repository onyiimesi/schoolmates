<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResultResource;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndTermResultController extends Controller
{
    public function endterm(Request $request){

        $user = Auth::user();

        $search = Result::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("student_id", $request->student_id)
        ->where("period", 'Second Half')
        ->where("term", $request->term)
        ->where("session", $request->session)->get();

        $s = ResultResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }
}
