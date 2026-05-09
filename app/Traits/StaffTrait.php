<?php

namespace App\Traits;

use App\Enum\StaffStatus;
use App\Models\AcademicPeriod;
use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Staff;
use App\Models\Student;
use App\Models\SubjectTeacher;
use App\Models\SubjectTeacherSubject;

trait StaffTrait
{
    use HttpResponses;

    protected function validatePreCreationConstraints($user, ?Campus $campus, $request)
    {
        if (!$campus) {
            return $this->error(null, 'Campus does not exist', 404);
        }

        if ($request->teacher_type === 'class teacher') {
            return $this->validateClassTeacherConstraint($user, $request);
        }

        if ($request->teacher_type === 'subject teacher') {
            return $this->validateSubjectTeacherConstraint($user, $request);
        }

        return null;
    }

    protected function validateClassTeacherConstraint($user, $request)
    {
        $class = ClassModel::where('sch_id', $user->sch_id)
            ->where('campus', $request->campus)
            ->where('class_name', $request->class_assigned)
            ->first();

        if (! $class) {
            return $this->error(null, "The class '{$request->class_assigned}' does'nt exist.", 404);
        }

        $classAlreadyTaken = Staff::where('sch_id', $user->sch_id)
            ->where('campus', $request->campus)
            ->where('class_assigned', $request->class_assigned)
            ->where('status', StaffStatus::ACTIVE)
            ->exists();

        if ($classAlreadyTaken) {
            return $this->error(null, "The class '{$request->class_assigned}' is already assigned.", 409);
        }
    }

    protected function validateSubjectTeacherConstraint($user, $request)
    {
        $conflicts = $this->checkSubjectAssignmentConflicts(
            $user->sch_id,
            $request->campus,
            $request->subject_assignments
        );

        return !empty($conflicts)
            ? $this->error(['conflicts' => $conflicts], 'Some subjects are already assigned.', 409)
            : null;
    }

    protected function performStaffCreation($request, $user, Campus $campus)
    {
        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
        $imagePath = $request->image ? uploadImage($request->image, 'staff', $cleanSchId) : [];
        $signaturePath = $request->signature ? uploadSignature($request->signature, 'signature', $cleanSchId) : [];

        $staff = Staff::create([
            'sch_id' => $user->sch_id,
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type,
            'designation_id' => $request->designation_id,
            'department' => $request->department,
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'username' => Staff::generateUsername($request->firstname, $request->surname),
            'email' => $request->email,
            'gender' => $request->gender,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'class_assigned' => $request->teacher_type === 'class teacher' ? $request->class_assigned : null,
            'image' => $imagePath['url'] ?? null,
            'signature' => $signaturePath['url'] ?? null,
            'teacher_type' => $request->teacher_type,
            'is_preschool' => $campus->is_preschool ? 'true' : 'false',
            'file_id' => $imagePath['file_id'] ?? null,
            'sig_id' => $signaturePath['file_id'] ?? null,
            'password' => bcrypt($request->password),
            'pass_word' => $request->password,
            'status' => StaffStatus::ACTIVE,
        ]);

        $this->handlePostCreationAssignments($staff, $request);

        return $this->success($staff->load('subjectTeachers'), 'Staff Created Successfully', 201);
    }

    protected function handlePostCreationAssignments(Staff $staff, $request): void
    {
        if ($request->teacher_type === 'class teacher') {
            Student::where('sch_id', $staff->sch_id)
                ->where('campus', $staff->campus)
                ->where('present_class', $request->class_assigned)
                ->update([
                    'teacher_firstname' => $staff->firstname,
                    'teacher_surname' => $staff->surname,
                    'teacher_middlename' => $staff->middlename,
                ]);
        }

        if ($request->teacher_type === 'subject teacher') {
            $this->createSubjectTeacherAssignments($staff, $request->subject_assignments);
        }
    }

    /**
     * Check for conflicts before any DB write.
     * Subjects are stored as [{"name": "ENGLISH LANGUAGE"}, ...] in the subject column.
     */
    protected function checkSubjectAssignmentConflicts(
        string $schId,
        string $campus,
        array $subjectAssignments,
        ?string $excludeStaffId = null // null on create, staff->id on update
    ): array {
        $conflicts = [];

        $classIds = array_column($subjectAssignments, 'class_id');
        if (count($classIds) !== count(array_unique($classIds))) {
            $conflicts[] = 'Duplicate classes found in your subject assignments.';
            return $conflicts;
        }

        foreach ($subjectAssignments as $assignment) {
            $classId = $assignment['class_id'];
            $class = ClassModel::find($classId);
            $className = $class?->class_name ?? $classId;
            $subjectNames = collect($assignment['subjects'])
                ->map(fn($s) => strtoupper(trim($s['name'])))
                ->toArray();

            $query = SubjectTeacherSubject::where('sch_id', $schId)
                ->where('campus', $campus)
                ->where('class_id', $classId)
                ->whereIn('subject_name', $subjectNames);

            // On update: ignore the current staff's own existing assignments
            if ($excludeStaffId) {
                $query->where('staff_id', '!=', $excludeStaffId);
            }

            $taken = $query->pluck('subject_name')->toArray();

            foreach ($taken as $takenSubject) {
                $conflicts[] = "'{$takenSubject}' is already assigned for class '{$className}'.";
            }
        }

        return $conflicts;
    }

    /**
     * Bulk-create SubjectTeacher records for each class+subjects pair.
     */
    protected function createSubjectTeacherAssignments(Staff $staff, array $subjectAssignments): void
    {
        $period = AcademicPeriod::where('sch_id', $staff->sch_id)
            ->where('campus', $staff->campus)
            ->first();

        foreach ($subjectAssignments as $assignment) {
            $class = ClassModel::findOrFail($assignment['class_id']);
            $subjectNames = collect($assignment['subjects'])
                ->map(fn($s) => strtoupper(trim($s['name'])))
                ->toArray();

            // Legacy JSON format — kept for backward compatibility
            $subjectJson = collect($subjectNames)
                ->map(fn($name) => ['name' => $name])
                ->toArray();

            $subjectTeacher = SubjectTeacher::create([
                'sch_id' => $staff->sch_id,
                'campus' => $staff->campus,
                'term' => $period->term ?? null,
                'session' => $period->session ?? null,
                'class_id' => $class->id,
                'class_name' => $class->class_name,
                'staff_id' => $staff->id,
                'subject' => $subjectJson, // JSON column stays in sync
            ]);

            // Relational rows — the flexible, queryable source of truth
            $relationalRows = collect($subjectNames)->map(fn($name) => [
                'sch_id' => $staff->sch_id,
                'campus' => $staff->campus,
                'term' => $period->term ?? null,
                'session' => $period->session ?? null,
                'subject_teacher_id' => $subjectTeacher->id,
                'subject_name' => $name,
                'staff_id' => $staff->id,
                'class_id' => $class->id,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            SubjectTeacherSubject::insert($relationalRows);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // UPDATE VALIDATION
    // ─────────────────────────────────────────────────────────────

    protected function validateUpdateConstraints($user, Staff $staff, $request): mixed
    {
        $teacherType = $request->teacher_type ?? $staff->teacher_type;

        if ($teacherType === 'class teacher' && $request->has('class_assigned')) {
            return $this->validateClassTeacherUpdateConstraint($user, $staff, $request);
        }

        if ($teacherType === 'subject teacher' && $request->has('subject_assignments')) {
            return $this->validateSubjectTeacherUpdateConstraint($user, $staff, $request);
        }

        return null;
    }

    protected function validateClassTeacherUpdateConstraint($user, Staff $staff, $request): mixed
    {
        // Same class — nothing to validate
        if ($staff->class_assigned === $request->class_assigned) {
            return null;
        }

        $classAlreadyTaken = Staff::where('sch_id', $user->sch_id)
            ->where('campus', $staff->campus)
            ->where('class_assigned', $request->class_assigned)
            ->where('status', StaffStatus::ACTIVE)
            ->where('id', '!=', $staff->id) // exclude self
            ->exists();

        return $classAlreadyTaken
            ? $this->error(null, "The class '{$request->class_assigned}' is already assigned.", 409)
            : null;
    }

    protected function validateSubjectTeacherUpdateConstraint($user, Staff $staff, $request): mixed
    {
        $conflicts = $this->checkSubjectAssignmentConflicts(
            $user->sch_id,
            $staff->campus,
            $request->subject_assignments,
            $staff->id // exclude current staff's own assignments
        );

        return !empty($conflicts)
            ? $this->error(['conflicts' => $conflicts], 'Some subjects are already assigned.', 409)
            : null;
    }

    protected function performStaffUpdate($request, $user, Staff $staff)
    {
        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
        $imagePath = $request->image ? uploadImage($request->image, 'staff', $cleanSchId) : null;
        $signaturePath = $request->signature ? uploadSignature($request->signature, 'signature', $cleanSchId) : null;

        $campus = Campus::where('name', $request->campus)->first();

        $staff->update(array_filter([
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type ?? null,
            'designation_id' => $request->designation_id,
            'department' => $request->department,
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'email' => $request->email,
            'gender' => $request->gender,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'teacher_type' => $request->teacher_type,
            'class_assigned' => $this->resolveClassAssigned($request, $staff),
            'is_preschool' => $campus->is_preschool ? 'true' : 'false',
            'image' => $imagePath['url'] ?? $staff->image,
            'signature' => $signaturePath['url'] ?? $staff->signature,
            'file_id' => $imagePath['file_id']  ?? $staff->file_id,
            'sig_id' => $signaturePath['file_id'] ?? $staff->sig_id,
        ], fn($value) => !is_null($value)));

        $staff->refresh();

        $this->handlePostUpdateAssignments($staff, $request);

        return $this->success($staff->load('subjectTeachers'), 'Staff Updated Successfully');
    }

    protected function handlePostUpdateAssignments(Staff $staff, $request): void
    {
        $teacherType = $staff->teacher_type;

        // Switching TO class teacher — clean up subject teacher records
        if ($teacherType === 'class teacher') {
            $this->clearSubjectTeacherAssignments($staff);
            $this->updateStudentTeacherInfo($staff, $request->class_assigned ?? $staff->class_assigned);
        }

        // Switching TO subject teacher — clear class assignment, sync subjects
        if ($teacherType === 'subject teacher') {
            $this->clearClassTeacherAssignment($staff);

            if ($request->has('subject_assignments')) {
                $this->syncSubjectTeacherAssignments($staff, $request->subject_assignments);
            }
        }
    }

    /**
     * Full sync: reconciles the new desired state against existing records.
     * - Adds new class+subject rows
     * - Removes dropped subjects
     * - Removes dropped classes entirely
     */
    protected function syncSubjectTeacherAssignments(Staff $staff, array $subjectAssignments): void
    {
        $period = AcademicPeriod::where('sch_id', $staff->sch_id)
            ->where('campus', $staff->campus)
            ->first();

        $incomingClassIds = array_column($subjectAssignments, 'class_id');

        // Remove SubjectTeacher records for classes no longer in the new list
        SubjectTeacher::where('staff_id', $staff->id)
            ->whereNotIn('class_id', $incomingClassIds)
            ->each(function (SubjectTeacher $record) {
                $record->subjects()->delete(); // relational rows
                $record->delete();
            });

        foreach ($subjectAssignments as $assignment) {
            $class = ClassModel::findOrFail($assignment['class_id']);
            $subjectNames = collect($assignment['subjects'])
                ->map(fn($s) => strtoupper(trim($s['name'])))
                ->toArray();

            $subjectJson = collect($subjectNames)
                ->map(fn($name) => ['name' => $name])
                ->toArray();

            // Update existing or create new SubjectTeacher record for this class
            $subjectTeacher = SubjectTeacher::updateOrCreate(
                ['staff_id' => $staff->id, 'class_id' => $class->id],
                [
                    'sch_id' => $staff->sch_id,
                    'campus' => $staff->campus,
                    'term' => $period->term    ?? null,
                    'session' => $period->session  ?? null,
                    'class_name' => $class->class_name,
                    'subject' => $subjectJson, // keep JSON in sync
                ]
            );

            // Sync relational rows: delete removed subjects, insert new ones
            $existing = $subjectTeacher->subjects()->pluck('subject_name')->toArray();
            $toAdd = array_diff($subjectNames, $existing);
            $toRemove = array_diff($existing, $subjectNames);

            if (!empty($toRemove)) {
                $subjectTeacher->subjects()
                    ->whereIn('subject_name', $toRemove)
                    ->delete();
            }

            if (!empty($toAdd)) {
                SubjectTeacherSubject::insert(
                    collect($toAdd)->map(fn($name) => [
                        'subject_teacher_id' => $subjectTeacher->id,
                        'subject_name' => $name,
                        'staff_id' => $staff->id,
                        'class_id' => $class->id,
                        'sch_id' => $staff->sch_id,
                        'campus' => $staff->campus,
                        'term' => $period->term ?? null,
                        'session' => $period->session ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])->toArray()
                );
            }
        }
    }

    protected function updateStudentTeacherInfo(Staff $staff, string $classAssigned): void
    {
        Student::where('sch_id', $staff->sch_id)
            ->where('campus', $staff->campus)
            ->where('present_class', $classAssigned)
            ->update([
                'teacher_firstname' => $staff->firstname,
                'teacher_surname' => $staff->surname,
                'teacher_middlename' => $staff->middlename,
            ]);
    }

    protected function clearSubjectTeacherAssignments(Staff $staff): void
    {
        SubjectTeacher::where('staff_id', $staff->id)
            ->each(function (SubjectTeacher $record) {
                $record->subjects()->delete();
                $record->delete();
            });
    }

    protected function clearClassTeacherAssignment(Staff $staff): void
    {
        // Remove teacher reference from students previously under this staff
        if ($staff->class_assigned) {
            Student::where('sch_id', $staff->sch_id)
                ->where('campus', $staff->campus)
                ->where('present_class', $staff->class_assigned)
                ->where('teacher_surname', $staff->surname)
                ->update([
                    'teacher_firstname' => null,
                    'teacher_surname' => null,
                    'teacher_middlename' => null,
                ]);
        }

        $staff->update(['class_assigned' => null]);
    }

    /**
     * Determines what class_assigned should be on update.
     */
    private function resolveClassAssigned($request, Staff $staff): ?string
    {
        $teacherType = $request->teacher_type ?? $staff->teacher_type;

        if ($teacherType === 'class teacher') {
            return $request->class_assigned ?? $staff->class_assigned;
        }

        return null; // subject teachers have no class_assigned
    }
}
