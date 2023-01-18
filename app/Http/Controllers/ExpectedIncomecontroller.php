<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class ExpectedIncomecontroller extends Controller
{
    public function expected(){
        
        $amount = Invoice::sum('amount');
        $discount = Invoice::sum('discount');

        $total = $amount - $discount;

        return [
            'status' => 'true',
            'message' => 'Expected Income',
            'data' => $total
        ];

        
    }
}
