<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\TrainingRegistration */
class TrainingRegistrationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'registered_at' => $this->registered_at?->toIso8601String(),
            'training_course' => $this->whenLoaded('trainingCourse', fn () => [
                'id' => $this->trainingCourse->id,
                'title' => $this->trainingCourse->localizedTitle(),
                'start_date' => $this->trainingCourse->start_date?->toDateString(),
                'end_date' => $this->trainingCourse->end_date?->toDateString(),
            ]),
        ];
    }
}
