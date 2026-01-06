<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolsResource;
use App\Models\Schools;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolsController extends Controller
{
    use HttpResponses;

    public function schools() {
        $user = Auth::user();

        $school = SchoolsResource::collection(
            Schools::with([
                'activeSubscription',
                'currentAcademicPeriod',
                'subscriptions',
            ])->where('sch_id', $user->sch_id)
            ->get()
        );

        return $this->success($school, 'School detail');
    }

    public function dos(Request $request)
    {
        $user = Auth::user();

        $school = Schools::where('sch_id', $user->sch_id)->first();

        if (! $school) {
            return $this->error(null, 'School not found', 404);
        }

        $school->update([
            'dos' => $request->dos
        ]);

        return $this->success(null, 'Added Successfully');
    }

    public function getdos()
    {
        $user = Auth::user();

        $school = Schools::where('sch_id', $user->sch_id)->first();

        if (! $school) {
            return $this->error(null, 'School not found', 404);
        }

        return $this->success([
            'attributes' => [
                'dos' => $school->dos
            ]
        ], 'School detail');
    }
}
