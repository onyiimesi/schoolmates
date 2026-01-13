<?php

declare(strict_types=1);

namespace App\DTOs\LessonNote;

final readonly class CreateLessonNoteDTO
{
    public function __construct(
        public string $sch_id,
        public string $campus,
        public string $term,
        public string $session,
        public int $staff_id,
        public string $week,
        public string $subject_id,
        public string $class_id,
        public string $topic,
        public string $description,
        public string $date_from,
        public string $date_to,
        public string $file,
        public string $file_name,
        public string $submitted_by,
        public string $date_submitted,
        public string $status,
        public ?string $file_id,
        public ?string $sub_topic,
        public ?string $specific_objectives,
        public ?string $previous_lesson,
        public ?string $previous_knowledge,
        public ?string $set_induction,
        public ?string $methodology,
        public ?string $teaching_aid,
    )
    {}

    public function toArray(): array
    {
        return [
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'term' => $this->term,
            'session' => $this->session,
            'staff_id' => $this->staff_id,
            'week' => $this->week,
            'subject_id' => $this->subject_id,
            'class_id' => $this->class_id,
            'topic' => $this->topic,
            'description' => $this->description,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'file' => $this->file,
            'file_name' => $this->file_name,
            'submitted_by' => $this->submitted_by,
            'date_submitted' => $this->date_submitted,
            'status' => $this->status,
            'file_id' => $this->file_id,
            'sub_topic' => $this->sub_topic,
            'specific_objectives' => $this->specific_objectives,
            'previous_lesson' => $this->previous_lesson,
            'previous_knowledge' => $this->previous_knowledge,
            'set_induction' => $this->set_induction,
            'methodology' => $this->methodology,
            'teaching_aid' => $this->teaching_aid,
        ];
    }
}
