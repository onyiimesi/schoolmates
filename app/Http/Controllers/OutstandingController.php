<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class OutstandingController extends Controller
{
    public function outstanding(){
        
        $total_amount = Payment::sum('total_amount');
        $amount_paid = Payment::sum('amount_paid');

        $total = $total_amount - $amount_paid;

        return [
            'status' => 'true',
            'message' => 'Outstanding',
            'data' => $total
        ];

    }
}
