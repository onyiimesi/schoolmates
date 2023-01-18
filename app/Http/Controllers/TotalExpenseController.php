<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;

class TotalExpenseController extends Controller
{
    public function totalexpense(){
        
        $amount = Expenses::sum('amount');

        return [
            'status' => 'true',
            'message' => 'Total Expense',
            'data' => $amount
        ];

    }
}
