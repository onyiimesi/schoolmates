<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function discount(){
        
        $discount_amount = Invoice::sum('discount_amount');

        return [
            'status' => 'true',
            'message' => 'Discount',
            'data' => $discount_amount
        ];

    }
}
