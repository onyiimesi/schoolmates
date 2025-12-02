<?php

namespace App\Http\Controllers;

use App\Services\GeneralService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AssignedVehicleController extends Controller
{
    use HttpResponses;

    public function __construct(
        protected GeneralService $generalService
    )
    {}

    public function getVehicle(): JsonResponse
    {
        return $this->generalService->getVehicle();
    }

    public function getVehicles(): JsonResponse
    {
        return $this->generalService->getVehicles();
    }
}
