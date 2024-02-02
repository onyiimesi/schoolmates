<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Models\AcademicPeriod;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentInvoiceController extends Controller
{
    public function studentinvoices(){

        $stud = Auth::user();
        $period = AcademicPeriod::where('sch_id', $stud->sch_id)
        ->where('campus', $stud->campus)
        ->first();

        $invoice = new InvoiceResource(
            Invoice::where('sch_id', $stud->sch_id)
            ->where('campus', $stud->campus)
            ->where('student_id', $stud->id)
            ->where('term', $period->term)
            ->where('session', $period->session)->first()
        );

        return [
            'status' => 'true',
            'message' => 'My Invoice',
            'data' => $invoice
        ];
    }

    public function studentprevinvoices(){

        $stud = Auth::user();

        $invoice = InvoiceResource::collection(
            Invoice::where('sch_id', $stud->sch_id)
            ->where('campus', $stud->campus)
            ->where('student_id', $stud->id)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Previous Invoice',
            'data' => $invoice
        ];
    }
}
