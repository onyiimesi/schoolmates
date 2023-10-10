<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicPeriodRequest;
use App\Http\Resources\AcademicPeriodResource;
use App\Models\AcademicPeriod;
use App\Models\AcademicSessions;
use App\Models\Schools;
use Illuminate\Http\Request;

class AcademicPeriodController extends Controller
{
    public function changeperiod(AcademicPeriodRequest $request){

        $request->validated($request->all());

        $aced = AcademicPeriod::first();
        $sch = Schools::first();

        if(empty($aced)){

            $aced = AcademicPeriod::create([
                'sch_id' => $sch->sch_id,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
            ]);

            AcademicSessions::create([
                'academic_session' => $request->session,
            ]);

            return [
                "status" => 'true',
                "message" => 'Academic Period Added Successfully',
                "data" => $aced
            ];

        }else if(!empty($aced)){

            $aced->update([
                'sch_id' => $sch->sch_id,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
            ]);

            $sess = AcademicSessions::where('academic_session', $request->session)->first();

            if(!$sess){

                AcademicSessions::create([
                    'academic_session' => $request->session,
                ]);

            }

            $aaa = new AcademicPeriodResource($aced);

            return [
                "status" => 'true',
                "message" => 'Academic Period Updated Successfully',
                "data" => $aaa
            ];

        }

    }

    public function getperiod(){

        $getaca = AcademicPeriod::get();

        return [
            "status" => 'true',
            "message" => 'Academic Period',
            "data" => $getaca
        ];

    }

    public function getsessions(){

        $getsess = AcademicSessions::get();

        return [
            "status" => 'true',
            "message" => 'Academic Sessions',
            "data" => $getsess
        ];

    }
}
