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

    public function getVehicle()
    {
        $user = Auth::user();
        $student = Student::findOrFail($user->id);

        $data = BusRoutingResource::collection(
            BusRouting::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('student_id', $student->id)
            ->get()
        );

        return $this->success($data, "Your Assigned Bus");
    }

    public function getVehicles()
    {
        $user = Auth::user();

        $data = BusRoutingResource::collection(
            BusRouting::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return $this->success($data, "Assigned Bus");
    }
}
