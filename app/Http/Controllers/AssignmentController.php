<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentPublishRequest;
use App\Http\Requests\AssignmentResultRequest;
use App\Http\Requests\CreateObjectiveRequest;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use App\Services\AssignmentService;

class AssignmentController extends Controller
{
    use HttpResponses;

    protected $assignmentService;
    protected $user;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
        $this->user = Auth::user();
    }

    public function objective(CreateObjectiveRequest $request): JsonResponse
    {
        return $this->assignmentService->objective($request, $this->user);
    }

    public function theory(Request $request): JsonResponse
    {
        return $this->assignmentService->theory($request, $this->user);
    }

    public function assign(Request $request): JsonResponse
    {
        return $this->assignmentService->assign($request, $this->user);
    }

    public function objectiveAnswer(Request $request): JsonResponse
    {
        return $this->assignmentService->objectiveAnswer($request, $this->user);
    }

    public function theoryAnswer(Request $request): JsonResponse
    {
        return $this->assignmentService->theoryAnswer($request, $this->user);
    }

    public function getAnswer(Request $request): JsonResponse
    {
        return $this->assignmentService->getAnswer($request, $this->user);
    }

    public function objectiveMark(Request $request): JsonResponse
    {
        return $this->assignmentService->objectiveMark($request, $this->user);
    }

    public function updateObjectiveMark(Request $request): JsonResponse
    {
        return $this->assignmentService->updateObjectiveMark($request);
    }

    public function theoryMark(Request $request): JsonResponse
    {
        return $this->assignmentService->theoryMark($request, $this->user);
    }

    public function updateTheoryMark(Request $request): JsonResponse
    {
        return $this->assignmentService->updateTheoryMark($request, $this->user);
    }

    public function marked(Request $request): JsonResponse
    {
        return $this->assignmentService->marked($request, $this->user);
    }

    public function markedByStudent(Request $request): JsonResponse
    {
        return $this->assignmentService->markedByStudent($request, $this->user);
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
        return $this->assignmentService->result($request, $this->user);
    }

    public function resultAssign(Request $request)
    {
        return $this->assignmentService->resultAssign($request, $this->user);
    }

    public function getStudentResult(Request $request)
    {
        return $this->assignmentService->getStudentResult($request, $this->user);
    }

    public function publish(AssignmentPublishRequest $request)
    {
        return $this->assignmentService->publish($request, $this->user);
    }
}
