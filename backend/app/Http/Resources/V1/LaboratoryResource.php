<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Laboratory */
class LaboratoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->localizedName(),
            'description' => $this->localizedDescription(),
            'safety_rules' => app()->getLocale() === 'lo' ? $this->safety_rules_lo : $this->safety_rules_en,
            'location_no' => $this->location_no,
            'building' => $this->building,
            'floor' => $this->floor,
            'room_name' => $this->room_name,
            'photo_url' => $this->photo ? asset('storage/'.$this->photo) : null,
            'responsible_staff' => $this->whenLoaded('responsibleUser', fn () => $this->responsibleUser?->name),
            'equipment_count' => $this->when(
                $this->relationLoaded('equipment') || isset($this->equipment_count),
                fn () => $this->equipment_count ?? $this->equipment->count()
            ),
        ];
    }
}
