<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice = InvoiceResource::collection(Invoice::get());

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

        $invoice_number = random_int(10, 9999999);

        $inv = Invoice::create([
            'admission_number' => $request->admission_number,
            'fullname' => $request->fullname,
            'class' => $request->class,
            'feetype' => $request->feetype,
            'amount' => $request->amount,
            'discount' => $request->discount,
            'discount_amount' => $request->discount_amount,
            'term' => $request->term,
            'session' => $request->session,
            'invoice_no' => $invoice_number,
        ]);

        return [
            "status" => 'true',
            "message" => 'Invoice Added Successfully',
            "data" => $inv
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
