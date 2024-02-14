<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutstandingController extends Controller
{
    public function outstanding(){
        $user = Auth::user();

        $invoice = Invoice::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->get();

        $invoice_fee = $invoice->pluck('feetype');
        $total_amount = 0;

        foreach ($invoice_fee as $fees) {
            $total_amount += collect($fees)->sum('discount_amount');
        }
        
        $amount_paid = Payment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->sum('amount_paid');

        $total = $total_amount - $amount_paid;
        
        return [
            'status' => 'true',
            'message' => 'Outstanding',
            'data' => $total
        ];
    }
}
