<?php

namespace App\Observers;

use App\Models\StudentScore;

class StudentScoreObserver
{
    /**
     * Handle the StudentScore "created" event.
     */
    public function created(StudentScore $studentScore): void
    {
        $studentScore->fresh();
    }

    /**
     * Handle the StudentScore "updated" event.
     */
    public function updated(StudentScore $studentScore): void
    {
        $studentScore->fresh();
    }

    /**
     * Handle the StudentScore "deleted" event.
     */
    public function deleted(StudentScore $studentScore): void
    {
        $studentScore->fresh();
    }

    /**
     * Handle the StudentScore "restored" event.
     */
    public function restored(StudentScore $studentScore): void
    {
        $studentScore->fresh();
    }

    /**
     * Handle the StudentScore "force deleted" event.
     */
    public function forceDeleted(StudentScore $studentScore): void
    {
        $studentScore->fresh();
    }
}
