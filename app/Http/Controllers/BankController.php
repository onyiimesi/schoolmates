<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $banks = BankResource::collection(
            Bank::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Bank List',
            'data' => $banks
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();

        Bank::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'opening_balance' => $request->opening_balance,
            'account_number' => $request->account_number,
            'account_purpose' => $request->account_purpose
        ]);

        return [
            "status" => 'true',
            "message" => 'Bank Added Successfully'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        $bank_det = new BankResource($bank);

        return [
            'status' => 'true',
            'message' => 'Bank Detail',
            'data' => $bank_det
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        $bank->update($request->all());

        $banks = new BankResource($bank);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $banks
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();

        return response(null, 204);
    }
}
