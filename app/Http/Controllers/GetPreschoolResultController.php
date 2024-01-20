<?php

namespace App\Http\Controllers;

use App\Http\Resources\PreSchoolResultResource;
use App\Models\PreSchoolResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetPreschoolResultController extends Controller
{
    public function getResult(Request $request)
    {
        $user = Auth::user();

        $search = PreSchoolResult::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("student_id", $request->student_id)
        ->where("period", $request->period)
        ->where("term", $request->term)
        ->where("session", $request->session)->get();

        $s = PreSchoolResultResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];
    }

    public function getComputeResult(Request $request)
    {
        $user = Auth::user();

        $computed = PreSchoolResultResource::collection(
            PreSchoolResult::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where("period", $request->period)
            ->where("term", $request->term)
            ->where("session", $request->session)
            ->get()
        );

        return [
            'status' => 'success',
            'message' => '',
            'data' => $computed
        ];
    }
}
