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

    public function schools(){
        $user = Auth::user();

        $school = SchoolsResource::collection(
            Schools::where('sch_id', $user->sch_id)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'School Details',
            'data' => $school
        ];
    }

    public function dos(Request $request)
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $school = Schools::where('sch_id', $user->sch_id)
        ->first();

        $school->update([
            'dos' => $request->dos
        ]);

        return [
            'status' => 'true',
            'message' => 'Added Successfully'
        ];
    }

    public function getdos()
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $school = Schools::where('sch_id', $user->sch_id)
        ->first();

        return [
            'status' => 'true',
            'message' => 'Added Successfully',
            'attributes' => [
                'dos' => $school->dos
            ]
        ];
    }
}
