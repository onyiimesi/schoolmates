<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicPeriodRequest;
use App\Services\AcademicPeriodService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicPeriodController extends Controller
{
    use HttpResponses;

    public function __construct(protected AcademicPeriodService $academicPeriodService)
    {}

    public function changePeriod(AcademicPeriodRequest $request): JsonResponse
    {
        return $this->academicPeriodService->changePeriod($request);
    }

    public function getPeriod(): JsonResponse
    {
        return $this->academicPeriodService->getPeriod();
    }

    public function getSessions(): JsonResponse
    {
        return $this->academicPeriodService->getSessions();
    }

    public function setCurrentAcademicPeriod(Request $request): JsonResponse
    {
        return $this->academicPeriodService->setCurrentAcademicPeriod($request);
    }

    public function getCurrentAcademicPeriod(): JsonResponse
    {
        return $this->academicPeriodService->getCurrentAcademicPeriod();
    }
}
