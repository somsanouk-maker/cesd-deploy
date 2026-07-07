<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'parent_id',
        'laboratory_id',
        'category_id',
        'responsible_user_id',
        'code',
        'name_en',
        'name_lo',
        'brand',
        'model',
        'serial_number',
        'shipping_country',
        'unit',
        'quantity',
        'is_accessory',
        'specification_en',
        'specification_lo',
        'capability_en',
        'capability_lo',
        'photo',
        'manual_file',
        'availability_status',
    ];

    protected function casts(): array
    {
        return [
            'is_accessory' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'parent_id');
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(Equipment::class, 'parent_id');
    }

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function localizedName(): string
    {
        return app()->getLocale() === 'lo' ? $this->name_lo : $this->name_en;
    }

    public function localizedSpecification(): ?string
    {
        return app()->getLocale() === 'lo' ? $this->specification_lo : $this->specification_en;
    }

    public function localizedCapability(): ?string
    {
        return app()->getLocale() === 'lo' ? $this->capability_lo : $this->capability_en;
    }
}
