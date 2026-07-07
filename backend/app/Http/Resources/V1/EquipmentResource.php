<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Equipment */
class EquipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->localizedName(),
            'brand' => $this->brand,
            'model' => $this->model,
            'shipping_country' => $this->shipping_country,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'is_accessory' => $this->is_accessory,
            'specification' => $this->localizedSpecification(),
            'capability' => $this->localizedCapability(),
            'availability_status' => $this->availability_status,
            'photo_url' => $this->photo ? asset('storage/'.$this->photo) : null,
            'manual_url' => $this->manual_file ? asset('storage/'.$this->manual_file) : null,
            'laboratory' => $this->whenLoaded('laboratory', fn () => $this->laboratory ? [
                'id' => $this->laboratory->id,
                'code' => $this->laboratory->code,
                'name' => $this->laboratory->localizedName(),
            ] : null),
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->localizedName(),
            ] : null),
            'accessories' => AccessoryResource::collection($this->whenLoaded('accessories')),
        ];
    }
}
