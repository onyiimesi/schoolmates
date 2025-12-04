<?php

namespace App\Observers;

use App\Models\Result;
use App\Services\Cache\CacheInvalidationService;

class ResultObserver
{
    public function __construct(
        private CacheInvalidationService $cacheInvalidationService,
    ) {}

    /**
     * Handle the Result "created" event.
     */
    public function created(Result $result): void
    {
        $resultData = $this->getData($result);
        
        $this->cacheInvalidationService->refreshResultServiceCache(
            $resultData['data'],
            $resultData['user']
        );
    }

    /**
     * Handle the Result "updated" event.
     */
    public function updated(Result $result): void
    {
        $this->created($result);
    }

    /**
     * Handle the Result "deleted" event.
     */
    public function deleted(Result $result): void
    {
        $this->created($result);
    }

    /**
     * Handle the Result "restored" event.
     */
    public function restored(Result $result): void
    {
        $this->created($result);
    }

    /**
     * Handle the Result "force deleted" event.
     */
    public function forceDeleted(Result $result): void
    {
        $this->created($result);
    }

    private function getData($result): array
    {
        $data = [
            'student_id' => $result->student_id,
            'period' => $result->period,
            'term' => $result->term,
            'session' => $result->session,
            'result_type' => $result->result_type,
            'class' => $result->class_name,
        ];

        $user = (object) [
            'sch_id' => $result->sch_id,
            'campus' => $result->campus,
        ];

        return [
            'data' => $data,
            'user' => $user,
        ];
    }
}
