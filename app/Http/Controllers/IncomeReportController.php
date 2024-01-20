<?php

namespace App\Http\Controllers;

use App\Http\Resources\IncomeReportResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Bank;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeReportController extends Controller
{
    public function incomesearch(Request $request){
        $user = Auth::user();

        $search = Payment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("term", $request->term)
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
        $user = Auth::user();

        $searchs = Invoice::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("term", $request->term)
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
