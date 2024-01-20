<?php

namespace App\Http\Controllers;

use App\Models\Discounts;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function discount(){
        $user = Auth::user();

        $discount_amount = Invoice::where('sch_id', $user->sch_id)
        ->sum('discount_amount');

        return [
            'status' => 'true',
            'message' => 'Discount',
            'data' => $discount_amount
        ];
    }

    public function setupDiscount(Request $request){

        $request->validate([
            'value' => ['required']
        ]);

        $user = Auth::user();

        $dis = Discounts::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'value' => $request->value,
        ]);

        return [
            "status" => 'true',
            "message" => 'Created Successfully',
            "data" => $dis
        ];

    }
}
