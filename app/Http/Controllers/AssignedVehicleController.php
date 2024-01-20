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

        $bus = BusRoutingResource::collection(
            BusRouting::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('student_id', $stud->id)->get()
        );

        return [
            'status' => 'true',
            'message' => 'Your Assigned Bus',
            'data' => $bus
        ];
    }

    public function getvehicles(){
        $user = Auth::user();

        $bus = BusRoutingResource::collection(
            BusRouting::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Assigned Bus',
            'data' => $bus
        ];
    }
}
