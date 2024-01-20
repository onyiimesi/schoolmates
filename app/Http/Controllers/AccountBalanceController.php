<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountBalanceController extends Controller
{
    public function account(){
        $user = Auth::user();

        $payment = Payment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->sum('amount_paid');
        
        $expenses = Expenses::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->sum('amount');

        $total = $payment - $expenses;

        return [
            'status' => 'true',
            'message' => 'Account Balance',
            'data' => $total
        ];

    }
}
