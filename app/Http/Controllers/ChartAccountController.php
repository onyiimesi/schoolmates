<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChartAccountRequest;
use App\Http\Resources\ChartAccountResource;
use App\Models\ChartAccount;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class ChartAccountController extends Controller
{
    use HttpResponses;

    /**
     * @return array<string, mixed>
     */
    public function index(): array
    {
        /** @var Staff $user */
        $user = Auth::user();
        
        $chart = ChartAccountResource::collection(ChartAccount::where('sch_id', $user->sch_id)->get());

        return [
            'status' => 'true',
            'message' => 'Chart of Account List',
            'data' => $chart
        ];
    }


    /**
     * @return array<string, mixed>
     */
    public function store(ChartAccountRequest $request): array
    {
        $request->validated($request->all());

        /** @var Staff $user */
        $user = Auth::user();

        $chartacct = ChartAccount::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'name' => $request->name,
            'acct_type' => $request->acct_type,
        ]);

        return [
            "status" => 'true',
            "message" => 'Added Successfully',
            "data" => $chartacct
        ];
    }

    public function update(Request $request, int $id): void
    {
        //
    }

    public function destroy(int $id): void
    {
        //
    }
}
