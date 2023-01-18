<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffAttendanceRequest;
use App\Http\Resources\StaffAttendanceResource;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;

class StaffAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffatten = StaffAttendanceResource::collection(StaffAttendance::get());

        return [
            'status' => 'true',
            'message' => 'Attendance List',
            'data' => $staffatten
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

        $staffatt = StaffAttendance::create([
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
