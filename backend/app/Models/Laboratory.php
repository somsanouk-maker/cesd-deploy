<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Laboratory extends Model
{
    protected $fillable = [
        'code',
        'name_en',
        'name_lo',
        'description_en',
        'description_lo',
        'safety_rules_en',
        'safety_rules_lo',
        'location_no',
        'building',
        'floor',
        'room_name',
        'photo',
        'responsible_user_id',
        'status',
    ];

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
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
