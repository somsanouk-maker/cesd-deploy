<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SiteSetting */
class SiteSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'address' => app()->getLocale() === 'lo' ? $this->address_lo : $this->address_en,
            'facebook_url' => $this->facebook_url,
        ];
    }
}
