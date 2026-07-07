<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCourse extends Model
{
    protected $fillable = [
        'title_en',
        'title_lo',
        'description_en',
        'description_lo',
        'start_date',
        'end_date',
        'capacity',
        'fee',
        'mode',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function localizedTitle(): string
    {
        return app()->getLocale() === 'lo' ? $this->title_lo : $this->title_en;
    }

    public function localizedDescription(): ?string
    {
        return app()->getLocale() === 'lo' ? $this->description_lo : $this->description_en;
    }
}
