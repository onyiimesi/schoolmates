<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class LessonNoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'sch_id' => (string)$this->sch_id,
                'campus' => (string)$this->campus,
                'term' => (string)$this->term,
                'session' => (string)$this->session,
                'staff_id' => (int)$this->staff_id,
                'class_id' => (int)$this->class_id,
                'class_name' => (string) optional($this->class)->class_name,
                'subject_id' => (int)$this->subject_id,
                'subject' => (string) optional($this->subject)->subject,
                'topic' => (string)$this->topic,
                'description' => (string)$this->description,
                'file' => (string)$this->file,
                'file_name' => (string)$this->file_name,
                'submitted_by' => (string)$this->submitted_by,
                'date_from' => (string)$this->date_from,
                'date_to' => (string)$this->date_to,
                'sub_topic' => (string)$this->sub_topic,
                'specific_objectives' => (string)$this->specific_objectives,
                'previous_lesson' => (string)$this->previous_lesson,
                'previous_knowledge' => (string)$this->previous_knowledge,
                'set_induction' => (string)$this->set_induction,
                'methodology' => (string)$this->methodology,
                'teaching_aid' => (string)$this->teaching_aid,
                'week' => (int)$this->week,
                'status' => (string)$this->status,
                'date_submitted' => $this->date_submitted ? Carbon::parse($this->date_submitted)->format('d M Y, h:i A') : null,
                'date_approved' => $this->date_approved ? Carbon::parse($this->date_approved)->format('d M Y, h:i A') : null,
            ]
        ];
    }
}
