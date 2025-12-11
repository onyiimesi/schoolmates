<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrincipalCommentResource extends JsonResource
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
            'id' => (int) $this->id,
            'attributes' => [
                'hos_id' => (int) $this->hos_id,
                'hos_fullname' => (string) $this->hos_fullname,
                'hos_comment' => (string) $this->hos_comment,
                'signature' => (string) $this->signature,
            ]
        ];
    }
}
