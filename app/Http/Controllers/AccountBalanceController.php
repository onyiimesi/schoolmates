<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Payment;
use Illuminate\Http\Request;

class AccountBalanceController extends Controller
{
    public function account(){
        
        $payment = Payment::sum('amount_paid');
        $expenses = Expenses::sum('amount');

        $total = $payment - $expenses;

        return [
            'status' => 'true',
            'message' => 'Account Balance',
            'data' => $total
        ];

    }
}
