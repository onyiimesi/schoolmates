<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class OutstandingController extends Controller
{
    public function outstanding(){

        $total = Payment::sum('total_amount') - Payment::sum('amount_paid');

        return [
            'status' => 'true',
            'message' => 'Outstanding',
            'data' => $total
        ];

    }
}
