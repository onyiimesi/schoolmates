<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpensesReportResource;
use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class ExpensesReportController extends Controller
{
    /**
     * @return array<string, mixed>
     */
    public function expensesearch(Request $request): array
    {
        /** @var Staff */
        $user = Auth::user();

        $search = Expenses::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("term", $request->term)
        ->where("session", $request->session)
        ->get();

        $data = ExpensesReportResource::collection($search);

        return [
            'status' => 'true',
            'message' => 'Expenses Report',
            'data' => $data
        ];
    }
}
