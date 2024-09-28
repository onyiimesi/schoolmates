<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\FlipClassRequest;
use App\Services\FlipClass\FlipClassService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FlipClassController extends Controller
{
    public $service;

    public function __construct(FlipClassService $service)
    {
        $this->service = $service;
    }

    public function addFlipClass(FlipClassRequest $request): JsonResponse
    {
        return $this->service->addFlipClass($request);
    }
    
    public function getFlipClass(Request $request): JsonResponse
    {
        return $this->service->getFlipClass($request);
    }

    public function getOneFlipClass(Request $request): JsonResponse
    {
        return $this->service->getOneFlipClass($request);
    }

    public function editFlipClass(Request $request, $id): JsonResponse
    {
        return $this->service->editFlipClass($request, $id);
    }

    public function deleteFlipClass($id): JsonResponse
    {
        return $this->service->deleteFlipClass($id);
    }

    public function approveFlipClass($id): JsonResponse
    {
        return $this->service->approveFlipClass($id);
    }

    public function unapproveFlipClass($id): JsonResponse
    {
        return $this->service->unapproveFlipClass($id);
    }
}
