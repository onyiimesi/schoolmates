<?php

namespace App\Jobs;

use App\Models\Result;
use App\Services\Cache\CacheInvalidationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class InvalidateClassResults implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $schId,
        public string $campus,
        public string $className,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CacheInvalidationService $cacheInvalidationService): void
    {
        $query = Result::query()
            ->select(
                'student_id',
                'period',
                'term',
                'session',
                'result_type',
                'class_name',
            )
            ->where('sch_id', $this->schId)
            ->where('campus', $this->campus)
            ->where('class_name', $this->className)
            ->select('student_id', 'period', 'term', 'session', 'result_type', 'class_name');

        $user = (object) [
            'sch_id' => $this->schId,
            'campus' => $this->campus,
        ];

        $query->chunk(200, function ($results) use ($cacheInvalidationService, $user) {
            foreach ($results as $row) {
                $data = [
                    'student_id' => $row->student_id,
                    'period' => $row->period,
                    'term' => $row->term,
                    'session' => $row->session,
                    'result_type' => $row->result_type,
                    'class' => $row->class_name,
                ];

                $cacheInvalidationService->refreshResultServiceCache($data, $user);
            }
        });
    }
}
