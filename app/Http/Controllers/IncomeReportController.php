<?php

namespace App\Http\Controllers;

use App\Http\Resources\IncomeReportResource;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class IncomeReportController extends Controller
{
    public function incomesearch(Request $request){

        $search = Payment::where("term", $request->term)->where("session", $request->session)->get();

        $s = IncomeReportResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }
}
