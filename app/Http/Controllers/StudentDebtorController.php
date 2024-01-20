<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDebtorController extends Controller
{
    public function debtors(){
        $user = Auth::user();

        $cred = PaymentResource::collection(
            Payment::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('status', 'debtor')
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Debtors List',
            'data' => $cred
        ];
 
    }

    public function debtorsByTermSession(Request $request){
        $user = Auth::user();

        $debt = PaymentResource::collection(
            Payment::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->where('status', 'debtor')
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Debtors List',
            'data' => $debt
        ];
    }
}
