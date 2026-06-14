<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $plan = $this->school->schoolPayment->pricing ?? $this->school->pricing;

        return [
            'id' => (string) $this->id,
            'attributes' => [
                'sch_id' => (string) $this->sch_id,
                'campus' => (string) $this->campus,
                'designation_id' => (string) $this->designation_id,
                'designation' => (string) $this->designation?->designation_name,
                'department' => (string) $this->department,
                'surname' => (string) $this->surname,
                'firstname' => (string) $this->firstname,
                'middlename' => (string) $this->middlename,
                'username' => (string) $this->username,
                'email' => (string) $this->email,
                'phoneno' => (string) $this->phoneno,
                'gender' => (string) $this->gender,
                'address' => (string) $this->address,
                'image' => (string) $this->image,
                'pass_word' => (string) $this->pass_word,
                'campus_type' => (string) $this->campus_type,
                'is_preschool' => (string) $this->is_preschool,
                'signature' => (string) $this->signature,
                'teacher_type' => (string) $this->teacher_type,
                'class_assigned'  => $this->resolveClassAssigned(),
                'subjects' => $this->resolveSubjectAssignments(),
                'status' => (string) $this->status,
                'plan' => (string) $plan->plan,
            ]
        ];
    }

    /**
     * Class teacher  → returns the assigned class name string.
     * Subject teacher → returns null (their classes are inside subject_assignments).
     */
    private function resolveClassAssigned(): ?string
    {
        return $this->teacher_type === 'class teacher'
            ? (string) $this->class_assigned
            : null;
    }

    /**
     * Subject teacher → returns each class with its subjects.
     * Class teacher   → returns empty array.
     */
    private function resolveSubjectAssignments(): array
    {
        if ($this->teacher_type !== 'subject teacher') {
            return [];
        }

        return $this->subjectTeachers
            ->map(fn($record) => [
                'class_id' => (string) $record->class_id,
                'class_name' => (string) $record->class_name,
                'subjects' => $this->resolveSubjects($record),
            ])
            ->toArray();
    }

    /**
     * Resolves subjects as [{ id, name }] from the relational table.
     * Falls back to legacy JSON column if no relational rows exist.
     */
    private function resolveSubjects($subjectTeacher): array
    {
        if ($subjectTeacher->subjects->isNotEmpty()) {
            return $subjectTeacher->subjects
                ->map(fn($subject) => [
                    'id' => (string) $subject->id,
                    'name' => $subject->subject_name,
                ])
                ->toArray();
        }

        // Legacy JSON fallback
        return (new \Illuminate\Support\Collection($subjectTeacher->subject ?? []))
            ->map(fn($subject) => [
                'id' => null,
                'name' => $subject['name'],
            ])
            ->all();
    }
}
