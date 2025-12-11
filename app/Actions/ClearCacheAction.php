<?php

namespace App\Actions;

use App\Models\Student;
use App\Services\Cache\ClearCacheService;

class ClearCacheAction
{
    public function __construct(
        protected ClearCacheService $clearCacheService
    )
    {}

    public function handle($request, $studentId): void
    {
        $studentClass = Student::where('id', $studentId)->value('present_class');

        $data = [
            'student_id' => $studentId,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->input('session'),
            'result_type' => $request->result_type,
            'class' => $studentClass,
        ];

        $this->clearCacheService->clearResultServiceCache($data);
    }
}

