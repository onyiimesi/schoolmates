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

        $results = PreSchoolResult::with(['preschoolresultextracurricular'])
            ->where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $request->student_id,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session
            ])
            ->get();

        $data = PreSchoolResultResource::collection($results);

        return $this->success($data, 'Result Retrieved Successfully');
    }

    public function getComputeResult(Request $request)
    {
        $user = Auth::user();

        $computed = PreSchoolResultResource::collection(
            PreSchoolResult::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $request->student_id,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session
            ])
            ->get()
        );

        return $this->success($computed, 'Result Retrieved Successfully');
    }
}
