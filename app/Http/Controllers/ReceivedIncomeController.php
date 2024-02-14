<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceivedIncomeController extends Controller
{
    public function received(){
        $user = Auth::user();

        $amount = Payment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->sum('amount_paid');

        return [
            'status' => 'true',
            'message' => 'Received Income',
            'data' => $amount
        ];
    }
}
