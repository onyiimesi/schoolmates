<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $depart = DepartmentResource::collection(
            Department::where('sch_id', $user->sch_id
            ->where('campus', $user->campus)
            ->get()
        ));

        return [
            'status' => 'true',
            'message' => 'Department List',
            'data' => $depart
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        $request->validated($request->all());

        $departm = Department::create([
            'department_name' => $request->department_name,
            'department_id' => $request->department_id
        ]);

        return [
            "status" => 'true',
            "message" => 'Department Created Successfully',
            "data" => $departm
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        $departments = new DepartmentResource($department);

        return [
            'status' => 'true',
            'message' => 'Department Details',
            'data' => $departments
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $department->update($request->all());

        $depart = new DepartmentResource($department);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $depart
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return response(null, 204);
    }
}
