<?php

namespace App\Http\Resources;

use App\Models\Staff;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     
    public function toArray($request)
    {
        $staff = Staff::where('id', $this->user_id)->first();
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'user_id' => (string)$staff->surname .' '.$staff->firstname ,
                'event' => (string)$this->event,
                'auditable_type' => (string)$this->auditable_type,
                'old_values' => (array)$this->old_values,
                'new_values' => (array)$this->new_values,
                'created_at' => (string)$this->created_at,
                'updated_at' => (string)$this->updated_at,
            ]
        ];
    }
}
