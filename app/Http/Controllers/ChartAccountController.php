<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChartAccountRequest;
use App\Http\Resources\ChartAccountResource;
use App\Models\ChartAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $chart = ChartAccountResource::collection(ChartAccount::where('sch_id', $user->sch_id)->get());

        return [
            'status' => 'true',
            'message' => 'Chart of Account List',
            'data' => $chart
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChartAccountRequest $request)
    {
        $request->validated($request->all());

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
