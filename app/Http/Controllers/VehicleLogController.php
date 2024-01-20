<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleLogRequest;
use App\Http\Resources\VehicleLogResource;
use App\Models\VehicleLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $vehiclelog = VehicleLogResource::collection(
            VehicleLog::where('sch_id', $user->sch_id)
            ->where('sch_id', $user->sch_id)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Vehicle Log Review',
            'data' => $vehiclelog
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VehicleLogRequest $request)
    {
        $request->validated($request->all());
        $user = Auth::user();

        $vehiclelog = VehicleLog::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'vehicle_number' => $request->vehicle_number,
            'driver_name' => $request->driver_name,
            'route' => $request->route,
            'purpose' => $request->purpose,
            'mechanic_condition' => $request->mechanic_condition,
            'add_info' => $request->add_info,
            'date_out' => $request->date_out,
            'time_out' => $request->time_out,
        ]);

        return [
            "status" => 'true',
            "message" => 'Added Successfully',
            "data" => $vehiclelog
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
