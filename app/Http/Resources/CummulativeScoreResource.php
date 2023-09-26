<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CummulativeScoreResource extends JsonResource
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
            'subject' => $this['subject'],
            'First Term' => $this['First term'],
            'Second Term' => $this['Second Term'],
            'Third Term' => $this['Third Term'],
            'Total Score' => $this['Total Score'],
            'Average Score' => $this['Average Score'],
        ];
    }
}
