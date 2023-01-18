<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpensesReportResource;
use App\Models\Expenses;
use Illuminate\Http\Request;

class ExpensesReportController extends Controller
{
    public function expensesearch(Request $request){

        $search = Expenses::where("term", $request->term)->where("session", $request->session)->get();

        $s = ExpensesReportResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }
}
