<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeeRequest;
use App\Http\Resources\FeeResource;
use App\Models\AcademicPeriod;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array<string, mixed>
     */
    public function index(): array
    {
        /** @var Staff */
        $user = Auth::user();

        $fee = FeeResource::collection(
            Fee::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Fee List',
            'data' => $fee
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return array<string, mixed>
     */
    public function store(FeeRequest $request): array
    {
        $request->validated($request->all());

        /** @var Staff */
        $user = Auth::user();

        $period = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->first();

        if (! $period) {
            return [
                'status' => 'false',
                'message' => 'Academic Period Not Found',
                'data' => null
            ];
        }

        $fees = Fee::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'feetype' => $request->feetype,
            'amount' => $request->amount,
            'term' => $request->term,
            'session' => $period->session,
            'fee_status' => $request->fee_status,
            'category' => $request->category
        ]);

        return [
            "status" => 'true',
            "message" => 'Fee Added Successfully',
            "data" => $fees
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id): void
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function update(Request $request, Fee $fee): array
    {
        $fee->update($request->all());

        $data = new FeeResource($fee);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $data
        ];
    }

    public function destroy(Fee $fee): Response|ResponseFactory
    {
        $fee->delete();

        return response(null, 204);
    }
}
