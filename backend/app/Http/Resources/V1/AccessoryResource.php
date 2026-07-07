<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Equipment */
class AccessoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->localizedName(),
            'brand' => $this->brand,
            'model' => $this->model,
            'quantity' => $this->quantity,
        ];
    }
}
