<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentCreditorsRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class StudentCreditorsController extends Controller
{
    public function creditors(){
        
        $cred = PaymentResource::collection(Payment::where('status', 'creditor')->get());

        return [
            'status' => 'true',
            'message' => 'Creditors List',
            'data' => $cred
        ];

    }

    public function creditorsByTermSession(Request $request){
        
        $credi = PaymentResource::collection(Payment::where('term', $request->term)
        ->where('session', $request->session)
        ->where('status', 'creditor')
        ->get());

        return [
            'status' => 'true',
            'message' => 'Creditors List',
            'data' => $credi
        ];
 
    }
}
