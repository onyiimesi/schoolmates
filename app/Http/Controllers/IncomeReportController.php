<?php

namespace App\Http\Controllers;

use App\Http\Resources\IncomeReportResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Bank;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class IncomeReportController extends Controller
{
    public function incomesearch(Request $request){

        $search = Payment::where("term", $request->term)
        ->where("session", $request->session)
        ->get();

        $s = IncomeReportResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }

    public function invoicesearch(Request $request){

        $searchs = Invoice::where("term", $request->term)
        ->where("session", $request->session)
        ->get();

        $ss = InvoiceResource::collection($searchs);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $ss
        ];

    }

}
