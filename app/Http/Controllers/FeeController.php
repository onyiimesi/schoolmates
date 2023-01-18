<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeeRequest;
use App\Http\Resources\FeeResource;
use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fee = FeeResource::collection(Fee::get());

        return [
            'status' => 'true',
            'message' => 'Fee List',
            'data' => $fee
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeeRequest $request)
    {
        $request->validated($request->all());

        $fees = Fee::create([
            'feetype' => $request->feetype,
            'amount' => $request->amount
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fee $fee)
    {
        $fee->update($request->all());

        $feess = new FeeResource($fee);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $feess
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fee $fee)
    {
        $fee->delete();

        return response(null, 204);
    }
}
