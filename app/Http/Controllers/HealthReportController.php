<?php

namespace App\Http\Controllers;

use App\Http\Requests\HealthReportRequest;
use App\Models\HealthReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthReportController extends Controller
{
    public function report(HealthReportRequest $request){

        $request->validated($request->all());

        $user = Auth::user();

        $health = HealthReport::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'admission_number' => $request->admission_number,
            'student_id' => $request->student_id,
            'student_fullname' => $request->student_fullname,
            'date_of_incident' => $request->date_of_incident,
            'time_of_incident' => $request->time_of_incident,
            'condition' => $request->condition,
            'state' => $request->state,
            'report_details' => $request->report_details,
            'action_taken' => $request->action_taken,
            'recommendation' => $request->recommendation
        ]);

        return [
            'status' => 'true',
            'message' => 'Report Sent Successfully',
            'data' => $health
        ];

    }
}
