<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicPeriodRequest;
use App\Http\Resources\AcademicPeriodResource;
use App\Models\AcademicPeriod;
use App\Models\AcademicSessions;
use App\Models\Schools;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicPeriodController extends Controller
{
    use HttpResponses;

    public function changeperiod(AcademicPeriodRequest $request){

        $request->validated($request->all());
        $user = Auth::user();

        $academicPeriod  = AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('session', $request->session)
        ->first();

        $sch = Schools::where('sch_id', $user->sch_id)
        ->first();

        if(!$academicPeriod){

            $aced = AcademicPeriod::create([
                'sch_id' => $sch->sch_id,
                'campus' => $user->campus,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
            ]);

            AcademicSessions::create([
                'sch_id' => $sch->sch_id,
                'campus' => $user->campus,
                'academic_session' => $request->session,
            ]);

            return $this->success($aced, "Academic Period Added Successfully");

        }else {

            $academicPeriod ->update([
                'sch_id' => $sch->sch_id,
                'campus' => $user->campus,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
            ]);

            $sess = AcademicSessions::where('academic_session', $request->session)->first();

            if(!$sess){
                AcademicSessions::create([
                    'sch_id' => $sch->sch_id,
                    'campus' => $user->campus,
                    'academic_session' => $request->session,
                ]);
            }

            $aaa = new AcademicPeriodResource($academicPeriod);

            return $this->success($aaa, "Academic Period Updated Successfully");
        }
    }

    public function getperiod(){

        $user = Auth::user();
        $getaca = AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->get();

        return [
            "status" => 'true',
            "message" => 'Academic Period',
            "data" => $getaca
        ];
    }

    public function getsessions(){

        $user = Auth::user();
        $getsess = AcademicSessions::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->get();

        return [
            "status" => 'true',
            "message" => 'Academic Sessions',
            "data" => $getsess
        ];
    }
}
