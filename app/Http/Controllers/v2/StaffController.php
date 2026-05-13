<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\AddStaffRequest;
use App\Http\Requests\v2\UpdateStaffRequest;
use App\Models\Staff;
use App\Services\StaffService;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct(
        private readonly StaffService $staffService
    ) {}

    public function addStaff(AddStaffRequest $request)
    {
        return $this->staffService->addStaff($request);
    }

    public function updateStaff(UpdateStaffRequest $request, int $id)
    {
        $staff = Staff::find($id);

        if (! $staff) {
            return $this->error(null, "Staff not found", 404);
        }

        return $this->staffService->updateStaff($request, $staff);
    }
}
