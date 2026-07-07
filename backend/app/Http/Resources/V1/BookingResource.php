<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Booking */
class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_no' => $this->booking_no,
            'bookable_type' => $this->bookable_type,
            'bookable_name' => $this->bookableName(),
            'purpose' => $this->purpose,
            'start_at' => $this->start_at?->toIso8601String(),
            'end_at' => $this->end_at?->toIso8601String(),
            'status' => $this->status,
            'requires_advisor_approval' => $this->requires_advisor_approval,
            'advisor_note' => $this->advisor_note,
            'staff_note' => $this->staff_note,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
