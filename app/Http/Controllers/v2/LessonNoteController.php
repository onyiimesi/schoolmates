<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\LessonNoteRequest;
use App\Services\LessonNote\LessonNoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Actions\LessonNote\CreateLessonNoteAction;

class LessonNoteController extends Controller
{
    public function __construct(
        protected LessonNoteService $lessonNoteService
    )
    {}

    public function addLesson(LessonNoteRequest $request, CreateLessonNoteAction $createLessonNoteAction): JsonResponse
    {
        return $this->lessonNoteService->addLessonNote($request, $createLessonNoteAction);
    }

    public function getLesson(Request $request): JsonResponse
    {
        return $this->lessonNoteService->getLessonNote($request);
    }

    public function getOneLesson(Request $request): JsonResponse
    {
        return $this->lessonNoteService->getOneLessonNote($request);
    }

    public function editLesson(Request $request, $id): JsonResponse
    {
        return $this->lessonNoteService->editLessonNote($request, $id);
    }

    public function deleteLesson($id): JsonResponse
    {
        return $this->lessonNoteService->deleteLessonNote($id);
    }

    public function approveLesson($id): JsonResponse
    {
        return $this->lessonNoteService->approveLessonNote($id);
    }

    public function unapproveLesson($id): JsonResponse
    {
        return $this->lessonNoteService->unapproveLessonNote($id);
    }
}
