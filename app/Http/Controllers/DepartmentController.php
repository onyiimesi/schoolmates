<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    use HttpResponses;

    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        /** @var Staff $user */
        $user = Auth::user();
        $campus = $request->query('campus');

        $depart = DepartmentResource::collection(
            Department::where('sch_id', $user->sch_id)
            ->when($campus, function ($query) use ($campus) {
                return $query->where('campus', $campus);
            })
            ->get()
        );

        return $this->success($depart, 'All Departments Fetched Successfully');
    }

    public function store(DepartmentRequest $request): JsonResponse
    {
        $request->validated($request->all());

        /** @var Staff $user */
        $user = Auth::user();

        $departm = Department::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'department_name' => $request->department_name,
            'department_id' => $request->department_id
        ]);

        return $this->success($departm, 'Department Created Successfully');
    }

    public function show(Department $department): JsonResponse
    {
        $departments = new DepartmentResource($department);

        return $this->success($departments, 'Department Details');
    }

    public function update(Request $request, Department $department): JsonResponse
    {
        $department->update($request->all());

        $depart = new DepartmentResource($department);

        return $this->success($depart, 'Updated Successfully');
    }

    public function destroy(Department $department): Response|ResponseFactory
    {
        $department->delete();

        return response(null, 204);
    }
}
