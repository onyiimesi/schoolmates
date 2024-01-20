<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutstandingController extends Controller
{
    public function outstanding(){
        $user = Auth::user();

        $total = Payment::where('sch_id', $user->sch_id)
        ->sum('total_amount') - Payment::where('sch_id', $user->sch_id)
        ->sum('amount_paid');

        return [
            'status' => 'true',
            'message' => 'Outstanding',
            'data' => $total
        ];
    }
}
