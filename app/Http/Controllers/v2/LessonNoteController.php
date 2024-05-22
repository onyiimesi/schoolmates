<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\LessonNoteRequest;
use App\Services\LessonNote\LessonNoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonNoteController extends Controller
{
    public $service;

    public function __construct(LessonNoteService $lessonNoteService)
    {
        $this->service = $lessonNoteService;
    }

    public function addLesson(LessonNoteRequest $request): JsonResponse
    {
        return $this->service->addLessonNote($request);
    }
    
    public function getLesson(Request $request): JsonResponse
    {
        return $this->service->getLessonNote($request);
    }

    public function getOneLesson(Request $request): JsonResponse
    {
        return $this->service->getOneLessonNote($request);
    }

    public function editLesson(Request $request, $id): JsonResponse
    {
        return $this->service->editLessonNote($request, $id);
    }

    public function deleteLesson($id): JsonResponse
    {
        return $this->service->deleteLessonNote($id);
    }

    public function approveLesson($id): JsonResponse
    {
        return $this->service->approveLessonNote($id);
    }

    public function unapproveLesson($id): JsonResponse
    {
        return $this->service->unapproveLessonNote($id);
    }

}
