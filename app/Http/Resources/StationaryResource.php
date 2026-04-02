<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StationaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sch_id' => $this->sch_id,
            'campus' => $this->campus,
            'attributes' => [
                'name' => $this->name,
                'unique_id' => $this->unique_id,
                'cost_price' => $this->cost_price,
                'selling_price' => $this->selling_price,
                'quantity' => $this->quantity,
                'image' => $this->image,
                'created_at' => $this->created_at->toDateString()
            ],
            'relationships' => [
                'stationary_sales' => StationarySaleResource::collection($this->stationarySales),
                'stationary_purchases' => StationaryPurchaseResource::collection($this->stationaryPurchases),
            ]
        ];
    }
}
