<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffAttendanceRequest;
use App\Http\Resources\StaffAttendanceResource;
use App\Models\StaffAttendance;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAttendanceController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        $attend = StaffAttendance::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->paginate(25);

        $staffatten = StaffAttendanceResource::collection($attend);

        return $this->withPagination($staffatten, "Staff Attendance list");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StaffAttendanceRequest $request)
    {
        $request->validated($request->all());
        $user = Auth::user();

        $data = StaffAttendance::updateOrCreate(
            [
                'sch_id'   => $user->sch_id,
                'campus'   => $user->campus,
                'staff_id' => $request->staff_id,
                'date_in'  => $request->date_in,
            ],
            [
                'time_in'  => $request->time_in,
                'time_out' => $request->time_out,
                'date_out' => $request->date_out,
            ]
        );

        return $this->success($data, 'Attendance Created Successfully', 201);
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
