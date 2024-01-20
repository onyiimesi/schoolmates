<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\AcademicPeriod;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentFeeHistoryController extends Controller
{
    public function feehistory(){

        $stud = Auth::user();
        // $fullname = $stud->surname . ' '. $stud->firstname . ' '. $stud->middlename;
        $fullname = "{$stud->surname} {$stud->firstname} {$stud->middlename}";

        $period = AcademicPeriod::where('sch_id', $stud->sch_id)
        ->first();

        $payment = PaymentResource::collection(
            Payment::where('sch_id', $stud->sch_id)
            ->where('campus', $stud->campus)
            ->where('student_fullname', $fullname)
            ->where('term', $period->term)
            ->where('session', $period->session)->get()
        );

        return [
            'status' => 'true',
            'message' => 'Fee History',
            'data' => $payment
        ];
    }
}
