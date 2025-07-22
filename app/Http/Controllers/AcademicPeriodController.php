<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicPeriodRequest;
use App\Models\AcademicPeriod;
use App\Models\AcademicSessions;
use App\Models\Schools;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicPeriodController extends Controller
{
    use HttpResponses;

    public function changeperiod(AcademicPeriodRequest $request)
    {
        $user = Auth::user();

        $academicPeriod  = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('session', $request->session)
            ->first();

        $sch = Schools::where('sch_id', $user->sch_id)
            ->firstOrFail();

        if(! $academicPeriod){
            AcademicPeriod::create([
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

            return $this->success(null, "Academic Period Added Successfully", 201);

        }else {

            $academicPeriod ->update([
                'sch_id' => $sch->sch_id,
                'campus' => $user->campus,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
            ]);

            $academicSession = AcademicSessions::where('sch_id', $sch->sch_id)
                ->where('campus', $user->campus)
                ->where('academic_session', $request->session)
                ->first();

            if(! $academicSession){
                AcademicSessions::create([
                    'sch_id' => $sch->sch_id,
                    'campus' => $user->campus,
                    'academic_session' => $request->session,
                ]);
            }

            return $this->success(null, "Academic Period Updated Successfully");
        }
    }

    public function getperiod()
    {
        $user = Auth::user();
        $data = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get();

        return $this->success($data, "Academic Periods");
    }

    public function getsessions()
    {
        $user = Auth::user();
        $data = AcademicSessions::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get();

        return $this->success($data, "Academic Sessions");
    }

    public function setCurrentAcademicPeriod(Request $request)
    {
        $user = Auth::user();

        $academicPeriod  = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->first();

        if(! $academicPeriod) {
            return $this->error(null, "Academic period doesn't exist!", 404);
        }

        AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->update(['is_current_period' => 0]);

        $academicPeriod->update(['is_current_period' => 1]);

        return $this->success(null, "Current Academic Period Set Successfully");
    }

    public function getCurrentAcademicPeriod()
    {
        $user = Auth::user();

        $academicPeriod = AcademicPeriod::select('id', 'period', 'term', 'session', 'is_current_period')
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('is_current_period', true)
            ->first();

        if(! $academicPeriod) {
            return $this->error(null, 'Current period has not been set.', 404);
        }

        return $this->success($academicPeriod, 'Current period');
    }
}
