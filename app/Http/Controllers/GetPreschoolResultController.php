<?php

namespace App\Http\Controllers;

use App\Http\Resources\PreSchoolResultResource;
use App\Models\PreSchoolResult;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetPreschoolResultController extends Controller
{
    use HttpResponses;

    public function getResult(Request $request)
    {
        $user = Auth::user();

        $search = PreSchoolResult::with(['preschoolresultextracurricular'])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where("student_id", $request->student_id)
            ->where("period", $request->period)
            ->where("term", $request->term)
            ->where("session", $request->session)
            ->get();

        $data = PreSchoolResultResource::collection($search);

        return $this->success($data, 'Result Retrieved Successfully');
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

        return $this->success($computed, 'Result Retrieved Successfully');
    }
}
