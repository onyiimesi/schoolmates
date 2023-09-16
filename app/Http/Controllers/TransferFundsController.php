<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferFundsRequest;
use App\Http\Resources\TransferFundsResource;
use App\Models\TransferFunds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferFundsController extends Controller
{
    public function transferFunds(TransferFundsRequest $request){

        $request->validated($request->all());

        $user = Auth::user();

        $transfer = TransferFunds::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'from' => $request->from,
            'to' => $request->to,
            'memo' => $request->memo,
            'amount' => $request->amount,
            'transfer_date' => $request->transfer_date,
        ]);

        return [
            'status' => 'true',
            'message' => 'Transfered Successfully',
            'data' => $transfer
        ];

    }

    public function getFunds(){

        $funds = TransferFundsResource::collection(TransferFunds::get());

        return [
            'status' => 'true',
            'message' => 'List of Funds',
            'data' => $funds
        ];

    }

    public function getSingleFunds(Request $request){

        $funds = TransferFundsResource::collection(TransferFunds::where('id', $request->id)->get());

        return [
            'status' => 'true',
            'message' => '',
            'data' => $funds
        ];

    }

    public function EditFunds(Request $request){

        $funds = TransferFunds::where('id', $request->id)->first();

        if(!$funds){
            return $this->error('', 'Fund does not exist', 400);
        }

        $funds->update([
            'from' => $request->from,
            'to' => $request->to,
            'amount' => $request->amount,
            'memo' => $request->memo,
            'transfer_date' => $request->transfer_date,
        ]);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            'data' => $funds
        ];

    }

    public function DeleteFunds(Request $request){

        $funds = TransferFunds::where('id', $request->id)->first();

        $funds->delete();

        return response(null, 204);

    }
}
