<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonNoteException extends Exception
{
    public function __construct(string $message = "Lesson Note error", int $code = 400, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request): JsonResponse
    {
        return new JsonResponse([
            'error' => 'An unexpected error occurred',
            'message' => $this->getMessage(),
            'data' => []
        ], $this->getCode());
    }
}
