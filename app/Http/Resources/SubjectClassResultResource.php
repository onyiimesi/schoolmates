<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectClassResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $mergedIds = $this->collection->pluck('id')->implode(',');

        $mergedSubjects = $this->collection->flatMap(function ($item) {
            return $item->subjects->map(function ($subject) {
                return ['name' => $subject->subject];
            });
        })->unique('name')->values();

        return [
            'id' => $mergedIds,
            'attributes' => [
                'subject' => $mergedSubjects
            ],
        ];
    }
}
