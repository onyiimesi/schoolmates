<?php

namespace App\Services;

use App\Models\AcademicPeriod;
use App\Models\AcademicSessions;
use App\Models\Schools;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AcademicPeriodService
{
    use HttpResponses;

    public function changePeriod($request): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check if the academic period already exists
            $academicPeriod = AcademicPeriod::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->where('period', $request->period)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->first();

            // If it already exists, stop further processing and return an error
            if ($academicPeriod) {
                return $this->error(null, 'Academic period already exists', 400);
            }

            $school = Schools::where('sch_id', $user->sch_id)
                ->firstOrFail();

            return DB::transaction(function () use ($request, $user, $school) {
                // Proceed to create the new academic period and session if it doesn't exist
                AcademicPeriod::create([
                    'sch_id' => $school->sch_id,
                    'campus' => $user->campus,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                ]);

                // Create the academic session if it doesn't exist
                AcademicSessions::create([
                    'sch_id' => $school->sch_id,
                    'campus' => $user->campus,
                    'academic_session' => $request->session,
                ]);

                return $this->success(null, 'Academic Period Added Successfully', 201);
            });
        } catch (\Throwable $th) {
            return $this->error(null, $th->getMessage(), 400);
        }
    }

    public function getPeriod(): JsonResponse
    {
        $user = Auth::user();
        $data = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get();

        return $this->success($data, 'Academic Periods');
    }

    public function getSessions(): JsonResponse
    {
        $user = Auth::user();
        $data = AcademicSessions::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get();

        return $this->success($data, 'Academic Sessions');
    }

    public function setCurrentAcademicPeriod($request): JsonResponse
    {
        $user = Auth::user();

        $academicPeriod  = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->first();

        if (! $academicPeriod) {
            return $this->error(null, 'Academic period doesn\'t exist!', 404);
        }

        AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->update(['is_current_period' => 0]);

        $academicPeriod->update(['is_current_period' => 1]);

        return $this->success(null, 'Current Academic Period Set Successfully');
    }

    public function getCurrentAcademicPeriod(): JsonResponse
    {
        $user = Auth::user();

        $academicPeriod = AcademicPeriod::select('id', 'sch_id', 'campus', 'period', 'term', 'session', 'is_current_period')
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
