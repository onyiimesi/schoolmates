<?php

namespace App\Http\Controllers;

use App\Http\Resources\BusRoutingResource;
use App\Models\BusRouting;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignedVehicleController extends Controller
{
    use HttpResponses;

    public function getvehicle(){

        $user = Auth::user();

        $stud = Student::findOrFail($user->id);

        $bus = BusRoutingResource::collection(BusRouting::where('student_id', $stud->id)->get());

        return [
            'status' => 'true',
            'message' => 'Your Assigned Bus',
            'data' => $bus
        ];

    }

    public function getvehicles(){

        $bus = BusRoutingResource::collection(BusRouting::get());

        return [
            'status' => 'true',
            'message' => 'Assigned Bus',
            'data' => $bus
        ];

    }
}
