<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\News */
class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->localizedTitle(),
            'excerpt' => $this->localizedExcerpt(),
            'body' => $this->localizedBody(),
            'cover_image_url' => $this->cover_image ? asset('storage/'.$this->cover_image) : null,
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
