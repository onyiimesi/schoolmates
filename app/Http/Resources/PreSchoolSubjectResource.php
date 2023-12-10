<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PreSchoolSubjectResource extends JsonResource
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
                // 'sch_id' => (string)$this->sch_id,
                // 'campus' => (string)$this->campus,
                // 'period' => (string)$this->period,
                // 'term' => (string)$this->term,
                // 'session' => (string)$this->session,
                'subject' => (string)$this->subject,
                'topic' => (array)$this->topic,
                'category' => (string)$this->category,
            ]
        ];
    }
}
