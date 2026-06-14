<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpensesRequest;
use App\Http\Resources\ExpensesResource;
use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array<string, mixed>
     */
    public function index(): array
    {   
        /** @var Staff */
        $user = Auth::user();
        
        $data = ExpensesResource::collection(
            Expenses::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Expenses List',
            'data' => $data
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return array<string, mixed>
     */
    public function store(ExpensesRequest $request): array
    {
        $request->validated($request->all());

        /** @var Staff */
        $user = Auth::user();

        $expen = Expenses::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'term' => $request->term,
            'session' => $request->session,
            'expense_category' => $request->expense_category,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'payment_type' => $request->payment_type,
            'beneficiary' => $request->beneficiary,
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'purpose' => $request->purpose,
        ]);

        return [
            "status" => 'true',
            "message" => 'Added Successfully',
            "data" => $expen
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id): void
    {
        //
    }
}
