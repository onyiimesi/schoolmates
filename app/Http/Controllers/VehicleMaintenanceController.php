<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleMaintenanceRequest;
use App\Http\Resources\VehicleMaintenanceResource;
use App\Models\VehicleMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleMaintenanceController extends Controller
{
    public function maintenance(VehicleMaintenanceRequest $request){

        $request->validated($request->all());

        $user = Auth::user();

        $vehicle = VehicleMaintenance::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'staff_id' => $user->id,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_make' => $request->vehicle_make,
            'vehicle_number' => $request->vehicle_number,
            'driver_name' => $request->driver_name,
            'detected_fault' => $request->detected_fault,
            'mechanic_name' => $request->mechanic_name,
            'mechanic_phone' => $request->mechanic_phone,
            'cost_of_maintenance' => $request->cost_of_maintenance,
            'initial_payment' => $request->initial_payment
        ]);

        return [
            'status' => '',
            'message' => 'Added Successfully',
            'data' => $vehicle
        ];

    }

    public function getmaintenance(){

        $main = VehicleMaintenanceResource::collection(VehicleMaintenance::get());

        return [
            'status' => 'true',
            'message' => '',
            'data' => $main
        ];

    }
}
