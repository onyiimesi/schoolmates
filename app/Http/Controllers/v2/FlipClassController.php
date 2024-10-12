<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\FlipClassAssessmentRequest;
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

    public function addObjAssessment(FlipClassAssessmentRequest $request)
    {
        return $this->service->addObjAssessment($request);
    }

    public function addTheoryAssessment(Request $request)
    {
        return $this->service->addTheoryAssessment($request);
    }

    public function getSingleQuestion(Request $request, $id)
    {
        return $this->service->getSingleQuestion($request, $id);
    }

    public function getObjQuestions(Request $request)
    {
        return $this->service->getObjQuestions($request);
    }

    public function editObj(Request $request)
    {
        return $this->service->editObj($request);
    }

    public function editThoery(Request $request)
    {
        return $this->service->editThoery($request);
    }

    public function delAssessment($id)
    {
        return $this->service->delAssessment($id);
    }

    public function publish(Request $request)
    {
        $request->validate([
            'period' => ['required', 'string'],
            'term' => ['required', 'string'],
            'session' => ['required', 'string'],
            'question_type' => ['required', 'string'],
            'week' => ['required', 'string'],
            'is_publish' => ['required', 'numeric', 'in:0,1']
        ]);
        
        return $this->service->publish($request);
    }
}
