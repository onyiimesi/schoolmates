<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class ReceivedIncomeController extends Controller
{
    public function received(){
        
        $amount = Payment::sum('amount_paid');

        return [
            'status' => 'true',
            'message' => 'Received Income',
            'data' => $amount
        ];

        
    }
}
