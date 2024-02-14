<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\AcademicPeriod;
use App\Models\Payment;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
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
        $period = AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->first();

        $pay = Payment::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('term', $period->term)
        ->where('session', $period->session)
        ->with('invoice')
        ->get();

        $group = $pay->groupBy(['student_id']);
        $data = $group->map(function ($students, $studentId) {
            $name = $students->first();
            return [
                'sch_id' => $name->sch_id,
                'campus' => $name->campus,
                'term' => $name->term,
                'session' => $name->session,
                'student_id' => $studentId,
                'class_name' => $name->invoice->class,
                'student_fullname' => $name->student_fullname,
                'payment' => $students->map(function ($payment) {
                    return [
                        'invoice_id' => $payment->invoice_id,
                        'bank_name' => $payment->bank_name,
                        'account_name' => $payment->account_name,
                        'payment_method' => $payment->payment_method,
                        'amount_paid' => $payment->amount_paid,
                        'total_amount' => $payment->total_amount,
                        'amount_due' => $payment->amount_due,
                        'type' => $payment->type,
                        'status' => $payment->status,
                        'paid_at' => Carbon::parse($payment->created_at)->format('j M Y')
                    ];
                })->toArray()
            ];
        })->values()->toArray();

        if($data){
            return response()->json([
                'status' => "true",
                'message' => "Payment List",
                'data' => $data,
            ], 200);
        }

        return $this->success([], "Payment List", 200);
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

        $user = Auth::user();

        $period = AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->first();

        $amountPaid = (float) $request->amount_paid;
        $totalAmount = (float) $request->total_amount;

        $pays = Payment::where('invoice_id', $request->invoice_id)->get();

        $amount = 0;
        if (!$pays->isEmpty()) {
            foreach ($pays as $pay) {
                if ($pay->type == "part-payment") {
                    $amount = $pay->amount_due;
                    $due = $amount - $amountPaid;
                    $status = "debtor";
                }
                if($request->type == "complete-payment"){
                    $amount = $pay->amount_due;
                    $due = $amount - $amountPaid;
                    $status = "creditor";
                }
            }
        } else {
            $amount = (float) $request->amount_paid;
            $due = $totalAmount - $amount;
            if($due == 0){
                $status = "creditor";
            }else{
                $status = "debtor";
            }
        }

        Payment::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'student_id' => $request->student_id,
            'invoice_id' => $request->invoice_id,
            'term' => $period->term,
            'session' => $period->session,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'student_fullname' => $request->student_fullname,
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->amount_paid,
            'total_amount' => $request->total_amount,
            'amount_due' => $due,
            'type' => $request->type,
            'status' => $status
        ]);

        return $this->success(null, "Payment Added Successfully", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        $viewPayment = new PaymentResource($payment);

        return $this->success($viewPayment, "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());

        return $this->success(null, "Updated Successfully", 200);
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
