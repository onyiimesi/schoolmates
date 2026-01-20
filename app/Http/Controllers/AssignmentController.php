<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentPublishRequest;
use App\Http\Requests\AssignmentResultRequest;
use App\Http\Requests\CreateObjectiveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use App\Services\AssignmentService;

class AssignmentController extends Controller
{
    use HttpResponses;

    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    public function objective(CreateObjectiveRequest $request): JsonResponse
    {
        return $this->assignmentService->objective($request);
    }

    public function theory(Request $request): JsonResponse
    {
        return $this->assignmentService->theory($request);
    }

    public function assign(Request $request): JsonResponse
    {
        return $this->assignmentService->assign($request);
    }

    public function objectiveAnswer(Request $request): JsonResponse
    {
        return $this->assignmentService->objectiveAnswer($request);
    }

    public function theoryAnswer(Request $request): JsonResponse
    {
        return $this->assignmentService->theoryAnswer($request);
    }

    public function getAnswer(Request $request): JsonResponse
    {
        return $this->assignmentService->getAnswer($request);
    }

    public function objectiveMark(Request $request): JsonResponse
    {
        return $this->assignmentService->objectiveMark($request);
    }

    public function updateObjectiveMark(Request $request): JsonResponse
    {
        return $this->assignmentService->updateObjectiveMark($request);
    }

    public function theoryMark(Request $request): JsonResponse
    {
        return $this->assignmentService->theoryMark($request);
    }

    public function updateTheoryMark(Request $request): JsonResponse
    {
        return $this->assignmentService->updateTheoryMark($request);
    }

    public function marked(Request $request): JsonResponse
    {
        return $this->assignmentService->marked($request);
    }

    public function markedByStudent(Request $request): JsonResponse
    {
        return $this->assignmentService->markedByStudent($request);
    }

    public function editObjectiveAssign(Request $request): JsonResponse
    {
        return $this->assignmentService->editObjectiveAssign($request);
    }

    public function editTheoryAssign(Request $request): JsonResponse
    {
        return $this->assignmentService->editTheoryAssign($request);
    }

    public function delAssign(Request $request): JsonResponse
    {
        return $this->assignmentService->delAssign($request);
    }

    public function result (AssignmentResultRequest $request)
    {
        return $this->assignmentService->result($request);
    }

    public function resultAssign(Request $request)
    {
        return $this->assignmentService->resultAssign($request);
    }

    public function getStudentResult(Request $request)
    {
        return $this->assignmentService->getStudentResult($request);
    }

    public function publish(AssignmentPublishRequest $request)
    {
        return $this->assignmentService->publish($request);
    }
}
