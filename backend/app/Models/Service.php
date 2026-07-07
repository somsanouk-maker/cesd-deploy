<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'slug',
        'name_en',
        'name_lo',
        'category',
        'description_en',
        'description_lo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function localizedName(): string
    {
        return app()->getLocale() === 'lo' ? $this->name_lo : $this->name_en;
    }

    public function localizedDescription(): ?string
    {
        return app()->getLocale() === 'lo' ? $this->description_lo : $this->description_en;
    }
}
