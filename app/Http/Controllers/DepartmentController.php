<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $user = Auth::user();

        $depart = DepartmentResource::collection(
            Department::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return $this->success($depart, 'All Departments Fetched Successfully');
    }

    public function store(DepartmentRequest $request)
    {
        $request->validated($request->all());
        $user = Auth::user();

        $departm = Department::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'department_name' => $request->department_name,
            'department_id' => $request->department_id
        ]);

        return $this->success($departm, 'Department Created Successfully');
    }

    public function show(Department $department)
    {
        $departments = new DepartmentResource($department);

        return $this->success($departments, 'Department Details');
    }
    public function update(Request $request, Department $department)
    {
        $department->update($request->all());

        $depart = new DepartmentResource($department);

        return $this->success($depart, 'Updated Successfully');
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
