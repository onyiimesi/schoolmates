<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array<string, mixed>
     */
    public function index(): array
    {
        /** @var Staff|Student $user */
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
     * @return array<string, mixed>
     */
    public function store(BankRequest $request): array
    {
        $request->validated($request->all());

        /** @var Staff|Student $user */
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
     * @return array<string, mixed>
     */
    public function show(Bank $bank): array
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
     * @return array<string, mixed>
     */
    public function update(Request $request, Bank $bank): array
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
     * @return Response|ResponseFactory
     */
    public function destroy(Bank $bank): Response|ResponseFactory
    {
        $bank->delete();

        return response(null, 204);
    }
}
