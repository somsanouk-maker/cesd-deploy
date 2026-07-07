<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentCategory extends Model
{
    protected $fillable = ['name_en', 'name_lo'];

    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class, 'category_id');
    }

    public function localizedName(): string
    {
        return app()->getLocale() === 'lo' ? $this->name_lo : $this->name_en;
    }
}
