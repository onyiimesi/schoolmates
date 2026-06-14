<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassModel;
use App\Models\SubjectClass;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Staff;
use Illuminate\Support\Collection;

class AssignSubjectsController extends Controller
{
    use HttpResponses;

    public function assign(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => ['required', 'integer', 'exists:class_models,id'],
            'subjects' => ['required', 'array'],
        ]);

        /** @var Staff $user */
        $user = Auth::user();

        $period = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('is_current_period', true)
            ->first();

        if (! $period instanceof AcademicPeriod) {
            return $this->error(null, 'Academic period not set', 404);
        }

        $class = ClassModel::find($request->class_id);

        if (! $class instanceof ClassModel) {
            return $this->error(null, 'Class does not exist', 400);
        }

        DB::transaction(function () use ($user, $request, $period, $class) {
            // Fetch existing subjects for this session ONLY (no term)
            $existingSubjects = $this->getExistingSubjects($user, $period, $request->class_id);

            // Determine which subjects need to be removed
            $subjectsToDelete = $this->getSubjectsToDelete($existingSubjects, $request->subjects);

            // Delete subjects that were removed from the payload
            if ($subjectsToDelete->isNotEmpty()) {
                $this->deleteSubjects($user, $period, $request->class_id, $subjectsToDelete);
            }

            // Assign/update subjects for this session
            $this->assignSubjects($user, $period, $request->class_id, $request->subjects, $class);
        });

        return $this->success(null, 'Subjects assigned successfully');
    }

    /**
     * @return Collection<int, string>
     */
    protected function getExistingSubjects(
        Staff $user,
        AcademicPeriod $period,
        int $classId
    ): Collection {
        return SubjectClass::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('session', $period->session)
            ->where('class_id', $classId)
            ->pluck('subject');
    }

    /**
     * @param Collection<int, string> $existingSubjects
     * @param array<int, array{name: string}> $subjectsInPayload
     *
     * @return Collection<int, string>
     */
    protected function getSubjectsToDelete(
        Collection $existingSubjects,
        array $subjectsInPayload
    ): Collection {
        $subjectNamesInPayload = (new Collection($subjectsInPayload))->pluck('name');

        return $existingSubjects->diff($subjectNamesInPayload);
    }

    /**
     * @param Collection<int, string> $subjectsToDelete
     */
    protected function deleteSubjects(
        Staff $user,
        AcademicPeriod $period,
        int $classId,
        Collection $subjectsToDelete
    ): void {
        SubjectClass::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('session', $period->session)
            ->where('class_id', $classId)
            ->whereIn('subject', $subjectsToDelete)
            ->delete();
    }

    /**
     * @param array<int, array{name: string}> $subjectsInPayload
     */
    protected function assignSubjects(
        Staff $user,
        AcademicPeriod $period,
        int $classId,
        array $subjectsInPayload,
        ClassModel $class
    ): void {
        foreach ($subjectsInPayload as $subjectData) {
            SubjectClass::updateOrCreate(
                [
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
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