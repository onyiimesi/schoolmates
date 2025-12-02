<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignClassRequest;
use App\Models\Staff;
use App\Services\GeneralService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignClassController extends Controller
{
    use HttpResponses;

    public function __construct(
        protected GeneralService $generalService
    )
    {}

    public function assign(AssignClassRequest $request, Staff $staff): JsonResponse
    {
        return $this->generalService->assign($request, $staff);
    }
}
