<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffAttendanceRequest;
use App\Http\Resources\StaffAttendanceResource;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $attend = StaffAttendance::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->paginate(25);

        $staffatten = StaffAttendanceResource::collection($attend);

        return [
            'status' => 'true',
            'message' => 'Attendance List',
            'data' => $staffatten,
            'pagination' => [
                'current_page' => $attend->currentPage(),
                'last_page' => $attend->lastPage(),
                'per_page' => $attend->perPage(),
                'prev_page_url' => $attend->previousPageUrl(),
                'next_page_url' => $attend->nextPageUrl(),
            ],
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffAttendanceRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();

        $staffatt = StaffAttendance::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'staff_id' => $request->staff_id,
            'time_in' => $request->time_in,
            'date_in' => $request->date_in,
            'time_out' => $request->time_out,
            'date_out' => $request->date_out,
        ]);

        return [
            "status" => 'true',
            "message" => 'Attendance Created Successfully',
            "data" => $staffatt
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
