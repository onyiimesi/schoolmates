<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentCreditorsRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentCreditorsController extends Controller
{
    public function creditors(){
        $user = Auth::user();

        $cred = PaymentResource::collection(
            Payment::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('status', 'creditor')
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Creditors List',
            'data' => $cred
        ];

    }

    public function creditorsByTermSession(Request $request){
        $user = Auth::user();

        $credi = PaymentResource::collection(
            Payment::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->where('status', 'creditor')
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Creditors List',
            'data' => $credi
        ];
    }
}
