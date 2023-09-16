<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReleaseResultRequest;
use App\Models\Result;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ReleaseResultsController extends Controller
{
    use HttpResponses;

    public function release(ReleaseResultRequest $request){
        
        Result::where('term', $request->term)
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
