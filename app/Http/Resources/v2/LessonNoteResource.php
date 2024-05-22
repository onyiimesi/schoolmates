<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'class_name' => (string)optional($this->class)->class_name,
                'subject_id' => (int)$this->subject_id,
                'subject' => (string)optional($this->subject)->subject,
                'topic' => (string)$this->topic,
                'description' => (string)$this->description,
                'file' => (string)$this->file,
                'submitted_by' => (string)$this->submitted_by,
                'week' => (int)$this->week,
                'status' => (string)$this->status
            ]

        ];
    }
}
