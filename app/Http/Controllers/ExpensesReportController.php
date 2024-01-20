<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpensesReportResource;
use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensesReportController extends Controller
{
    public function expensesearch(Request $request){
        $user = Auth::user();

        $search = Expenses::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("term", $request->term)
        ->where("session", $request->session)
        ->get();

        $s = ExpensesReportResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];
    }
}
