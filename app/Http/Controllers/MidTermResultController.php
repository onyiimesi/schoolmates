<?php

namespace App\Http\Controllers;

use App\Enum\ResultStatus;
use App\Http\Resources\MidTermResultResource;
use App\Models\Result;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MidTermResultController extends Controller
{
    use HttpResponses;

    public function midterm(Request $request){

        $user = Auth::user();

        $search = Result::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $request->student_id,
                'period' => 'First Half',
                'term' => $request->term,
                'session' => $request->session,
                'result_type' => 'midterm',
                'status' => ResultStatus::RELEASED,
            ])
            ->get();

        $data = MidTermResultResource::collection($search);

        return $this->success($data, 'Mid term result');
    }

    public function first(Request $request){

        $user = Auth::user();

        $search = Result::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $request->student_id,
                'period' => 'First Half',
                'term' => $request->term,
                'session' => $request->session,
                'result_type' => 'first_assesment',
                'status' => ResultStatus::RELEASED,
            ])
            ->get();

        $data = MidTermResultResource::collection($search);

        return $this->success($data, 'Result');

    }

    public function second(Request $request){

        $user = Auth::user();

        $search = Result::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $request->student_id,
                'period' => 'First Half',
                'term' => $request->term,
                'session' => $request->session,
                'result_type' => 'second_assesment',
                'status' => ResultStatus::RELEASED,
            ])
            ->get();

        $data = MidTermResultResource::collection($search);

        return $this->success($data, 'Result');

    }
}
