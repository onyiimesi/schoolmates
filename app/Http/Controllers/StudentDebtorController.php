<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class StudentDebtorController extends Controller
{
    public function debtors(){
        
        $cred = PaymentResource::collection(Payment::where('status', 'debtor')->get());

        return [
            'status' => 'true',
            'message' => 'Debtors List',
            'data' => $cred
        ];

        
    }
}
