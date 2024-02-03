<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\AcademicPeriod;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{

    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $invoice = InvoiceResource::collection(
            Invoice::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Invoice List',
            'data' => $invoice
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();
        $period = AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->first();

        $inv = Invoice::where('student_id', $request->student_id)
        ->where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('term', $period->term)
        ->where('session', $period->session)
        ->first();

        if($inv){
            return $this->error(null, "Invoice already created", 400);
        }

        $invoice_number = random_int(10, 9999999);

        Invoice::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'admission_number' => $request->admission_number,
            'student_id' => $request->student_id,
            'fullname' => $request->fullname,
            'class' => $request->class,
            'feetype' => $request->fee,
            'amount' => $request->amount,
            'discount' => $request->discount,
            'discount_amount' => $request->discount_amount,
            'term' => $period->term,
            'session' => $period->session,
            'invoice_no' => $invoice_number,
        ]);

        return [
            "status" => 'true',
            "message" => 'Invoice Added Successfully'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'feetype' => $request->fee
        ]);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully'
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
