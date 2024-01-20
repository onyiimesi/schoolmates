<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReleaseResultRequest;
use App\Models\Result;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReleaseResultsController extends Controller
{
    use HttpResponses;

    public function release(ReleaseResultRequest $request){
        $user = Auth::user();

        Result::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->update([
            'status' => $request->status,
        ]);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
        ];
    }
}
