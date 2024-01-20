<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpectedIncomecontroller extends Controller
{
    public function expected(){
        $user = Auth::user();

        $amount = Invoice::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->sum('amount');

        $discount = Invoice::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->sum('discount');

        $total = $amount - $discount;

        return [
            'status' => 'true',
            'message' => 'Expected Income',
            'data' => $total
        ];
    }
}
