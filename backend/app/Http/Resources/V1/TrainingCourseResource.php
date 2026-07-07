<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\TrainingCourse */
class TrainingCourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->localizedTitle(),
            'description' => $this->localizedDescription(),
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'capacity' => $this->capacity,
            'fee' => $this->fee,
            'mode' => $this->mode,
        ];
    }
}
