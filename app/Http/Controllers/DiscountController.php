<?php

namespace App\Http\Controllers;

use App\Models\Discounts;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class DiscountController extends Controller
{
    /**
     * @return array<string, mixed>
     */
    public function discount(): array
    {
        /** @var Staff $user */
        $user = Auth::user();

        $discount_amount = Invoice::query()
            ->where('sch_id', $user->sch_id)
            ->sum('discount_amount');

        return [
            'status' => 'true',
            'message' => 'Discount',
            'data' => $discount_amount
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function setupDiscount(Request $request): array
    {
        $request->validate([
            'value' => ['required']
        ]);

        /** @var Staff $user */
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
