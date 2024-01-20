<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $pay = PaymentResource::collection(
            Payment::where('sch_id', $user->sch_id)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Payment List',
            'data' => $pay
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequest $request)
    {
        $request->validated($request->all());

        $amountPaid = (float) $request->amount_paid;
        $totalAmount = (float) $request->total_amount;

        if ($amountPaid == $totalAmount) {
            $due = 0;
            $status = "creditor";
        } else {
            $due = $totalAmount - $amountPaid;
            $status = "debtor";
        }

        $user = Auth::user();

        $pays = Payment::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'term' => $request->term,
            'session' => $request->session,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'student_id' => $request->student_id,
            'student_fullname' => $request->student_fullname,
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->amount_paid,
            'total_amount' => $request->total_amount,
            'amount_due' => $due,
            'remark' => $request->remark,
            'status' => $status,
        ]);

        return [
            "status" => 'true',
            "message" => 'Payment Added Successfully',
            "data" => $pays
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
    public function update(Request $request, $id)
    {
        //
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
