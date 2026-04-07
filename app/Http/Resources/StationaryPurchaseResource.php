<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StationaryPurchaseResource extends JsonResource
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
                'stationary_supplier_id' => $this->stationary_supplier_id,
                'date_supplied' => $this->date_supplied,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'stationary_id' => $this->stationary_id,
                'stationary_name' => $this->stationary?->name,
                'created_at' => $this->created_at->toDateString()
            ],
            'relationships' => [
                'stationary_supplier' => $this->whenLoaded('stationarySupplier', fn() => [
                    'id' => $this->stationarySupplier->id,
                    'first_name' => $this->stationarySupplier->first_name,
                    'last_name' => $this->stationarySupplier->last_name,
                    'phone_number' => $this->stationarySupplier->phone_number,
                    'address' => $this->stationarySupplier->address,
                ])
            ]
        ];
    }
}
