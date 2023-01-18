<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleLogRequest;
use App\Http\Resources\VehicleLogResource;
use App\Models\VehicleLog;
use Illuminate\Http\Request;

class VehicleLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehiclelog = VehicleLogResource::collection(VehicleLog::get());

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

        $vehiclelog = VehicleLog::create([
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
