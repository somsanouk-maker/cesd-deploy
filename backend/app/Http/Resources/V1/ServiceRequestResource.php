<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ServiceRequest */
class ServiceRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'request_no' => $this->request_no,
            'title' => $this->title,
            'description' => $this->description,
            'sample_information' => $this->sample_information,
            'required_date' => $this->required_date?->toDateString(),
            'status' => $this->status,
            'quotation_status' => $this->quotation_status,
            'quoted_amount' => $this->quoted_amount,
            'quotation_notes' => $this->quotation_notes,
            'quoted_at' => $this->quoted_at?->toIso8601String(),
            'staff_notes' => $this->staff_notes,
            'service' => $this->whenLoaded('service', fn () => [
                'id' => $this->service->id,
                'name' => $this->service->localizedName(),
            ]),
            'laboratory' => $this->whenLoaded('laboratory', fn () => $this->laboratory ? [
                'id' => $this->laboratory->id,
                'name' => $this->laboratory->localizedName(),
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
