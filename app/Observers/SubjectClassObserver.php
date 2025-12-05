<?php

namespace App\Observers;

use App\Jobs\InvalidateClassResults;
use App\Models\SubjectClass;

class SubjectClassObserver
{
    /**
     * Handle the SubjectClass "created" event.
     */
    public function created(SubjectClass $subjectClass): void
    {
        $this->refreshForClass($subjectClass);
    }

    /**
     * Handle the SubjectClass "updated" event.
     */
    public function updated(SubjectClass $subjectClass): void
    {
        $this->refreshForClass($subjectClass);
    }

    /**
     * Handle the SubjectClass "deleted" event.
     */
    public function deleted(SubjectClass $subjectClass): void
    {
        $this->refreshForClass($subjectClass);
    }

    /**
     * Handle the SubjectClass "restored" event.
     */
    public function restored(SubjectClass $subjectClass): void
    {
        $this->refreshForClass($subjectClass);
    }

    /**
     * Handle the SubjectClass "force deleted" event.
     */
    public function forceDeleted(SubjectClass $subjectClass): void
    {
        $this->refreshForClass($subjectClass);
    }

    private function refreshForClass(SubjectClass $subjectClass): void
    {
        dispatch(
        new InvalidateClassResults(
            schId: $subjectClass->sch_id,
            campus: $subjectClass->campus,
            className: $subjectClass->class_name
        )
    );
    }
}
