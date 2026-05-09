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

    public function updateStaff(UpdateStaffRequest $request, Staff $staff)
    {
        return $this->staffService->updateStaff($request, $staff);
    }
}
