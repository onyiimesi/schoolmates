<?php

namespace App\Exceptions;

use Exception;

class LessonNoteException extends Exception
{
    public function __construct($message = "Lesson Note error", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'An unexpected error occurred',
            'message' => $this->getMessage(),
            'data' => []
        ], $this->getCode());
    }
}
