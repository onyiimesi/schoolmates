<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicle = VehicleResource::collection(Vehicle::get());

        return [
            'status' => 'true',
            'message' => 'Vehicle List',
            'data' => $vehicle
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VehicleRequest $request)
    {
        $request->validated($request->all());

        $vehicle = Vehicle::create([
            'type' => $request->type,
            'make' => $request->make,
            'number' => $request->number,
            'drivername' => $request->drivername,
        ]);

        return [
            "status" => 'true',
            "message" => 'Added Successfully',
            "data" => $vehicle
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
    public function update(Request $request, Vehicle $vehicle)
    {
        $vehicle->update($request->all());

        $vehi = new VehicleResource($vehicle);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $vehi
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return response(null, 204);
    }
}
