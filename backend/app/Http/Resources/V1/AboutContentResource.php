<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\AboutContent */
class AboutContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lo = app()->getLocale() === 'lo';

        return [
            'title' => $lo ? $this->title_lo : $this->title_en,
            'background' => $lo ? $this->background_lo : $this->background_en,
            'vision' => $lo ? $this->vision_lo : $this->vision_en,
            'mission' => $lo ? $this->mission_lo : $this->mission_en,
            'objectives' => array_values(array_filter([
                $lo ? $this->objective1_lo : $this->objective1_en,
                $lo ? $this->objective2_lo : $this->objective2_en,
                $lo ? $this->objective3_lo : $this->objective3_en,
                $lo ? $this->objective4_lo : $this->objective4_en,
            ])),
            'organization' => [
                'director' => $lo ? $this->org_director_lo : $this->org_director_en,
                'deputyDirector' => $lo ? $this->org_deputy_director_lo : $this->org_deputy_director_en,
                'admin' => $lo ? $this->org_admin_lo : $this->org_admin_en,
                'technical' => $lo ? $this->org_technical_lo : $this->org_technical_en,
                'innovation' => $lo ? $this->org_innovation_lo : $this->org_innovation_en,
            ],
        ];
    }
}
