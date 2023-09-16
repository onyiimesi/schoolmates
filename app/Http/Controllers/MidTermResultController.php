<?php

namespace App\Http\Controllers;

use App\Http\Resources\MidTermResultResource;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MidTermResultController extends Controller
{
    public function midterm(Request $request){

        $user = Auth::user();

        $search = Result::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("student_id", $request->student_id)
        ->where("period", 'First Half')
        ->where("term", $request->term)
        ->where("session", $request->session)->get();

        $s = MidTermResultResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }
}
