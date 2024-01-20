<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TotalExpenseController extends Controller
{
    public function totalexpense(){
        $user = Auth::user();

        $amount = Expenses::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->sum('amount');

        return [
            'status' => 'true',
            'message' => 'Total Expense',
            'data' => $amount
        ];

    }
}
