<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'campus' => (string)$this->campus,
                'class_name' => (string)$this->class_name,
                'sub_class' => (string)$this->sub_class,
                'teachers' => $this->resolveTeachers(),
                'subjects' => $this->resolveAllSubjects(),
            ]
        ];
    }

    /**
     * Returns all teachers (class teacher + subject teachers) for this class.
     */
    private function resolveTeachers(): array
    {
        $teachers = [];

        // Class teacher
        if ($this->classTeacher) {
            $teachers[] = [
                'id' => (string) $this->classTeacher->id,
                'name' => $this->classTeacher->fullName(),
                'type' => 'class teacher',
                'subjects' => [],
            ];
        }

        // Subject teachers
        foreach ($this->subjectTeachers as $subjectTeacher) {
            if (!$subjectTeacher->staff) {
                continue;
            }

            $teachers[] = [
                'id' => (string) $subjectTeacher->staff->id,
                'name' => $subjectTeacher->staff->fullName(),
                'type' => 'subject teacher',
                'subjects' => $this->resolveSubjects($subjectTeacher),
            ];
        }

        return $teachers;
    }

    /**
     * Returns a flat, deduplicated list of all subjects taught in this class.
     */
    private function resolveAllSubjects(): array
    {
        return $this->subjectTeachers
            ->flatMap(fn($st) => $this->resolveSubjects($st))
            ->uniqueStrict('name')
            ->values()
            ->toArray();
    }

    /**
     * Resolves subjects as [{ id, name }] from the relational table,
     * falls back to the legacy JSON column if no relational rows exist.
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

        // Legacy JSON fallback — no id available
        return collect($subjectTeacher->subject ?? [])
            ->map(fn($subject) => [
                'id' => null,
                'name' => $subject['name'],
            ])
            ->toArray();
    }
}
