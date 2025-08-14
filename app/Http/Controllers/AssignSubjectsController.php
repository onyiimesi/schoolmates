<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassModel;
use App\Models\SubjectClass;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignSubjectsController extends Controller
{
    use HttpResponses;

    public function assign(Request $request)
    {
        $user = Auth::user();

        $period = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('is_current_period', true)
            ->first();

        if (! $period) {
            return $this->error(null, 'Academic period not set', 404);
        }

        $class = ClassModel::find($request->class_id);

        if (! $class) {
            return $this->error(null, 'Class does not exist', 400);
        }

        DB::transaction(function () use ($user, $request, $period, $class) {
            // Step 1: Get existing subjects for the class and period
            $existingSubjects = $this->getExistingSubjects($user, $period, $request->class_id);

            // Step 2: Find subjects that need to be deleted
            $subjectsToDelete = $this->getSubjectsToDelete($existingSubjects, $request->subjects);

            // Step 3: Delete subjects that are no longer in the payload
            if ($subjectsToDelete->isNotEmpty()) {
                $this->deleteSubjects($user, $period, $request->class_id, $subjectsToDelete);
            }

            // Step 4: Insert or update the subjects in the payload
            $this->assignSubjects($user, $period, $request->class_id, $request->subjects, $class);
        });

        return $this->success(null, 'Subjects assigned successfully');
    }

    protected function getExistingSubjects($user, $period, $classId)
    {
        return SubjectClass::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('term', $period->term)
            ->where('session', $period->session)
            ->where('class_id', $classId)
            ->pluck('subject');
    }

    protected function getSubjectsToDelete($existingSubjects, $subjectsInPayload)
    {
        $subjectNamesInPayload = collect($subjectsInPayload)->pluck('name');
        return $existingSubjects->diff($subjectNamesInPayload);
    }

    protected function deleteSubjects($user, $period, $classId, $subjectsToDelete)
    {
        SubjectClass::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('term', $period->term)
            ->where('session', $period->session)
            ->where('class_id', $classId)
            ->whereIn('subject', $subjectsToDelete)
            ->delete();
    }

    protected function assignSubjects($user, $period, $classId, $subjectsInPayload, $class)
    {
        foreach ($subjectsInPayload as $subjectData) {
            SubjectClass::updateOrCreate(
                [
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'term' => $period->term,
                    'session' => $period->session,
                    'class_id' => $classId,
                    'subject' => $subjectData['name'],
                ],
                [
                    'class_name' => $class->class_name,
                ]
            );
        }
    }
}
