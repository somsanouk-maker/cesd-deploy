<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'slug',
        'title_en',
        'title_lo',
        'excerpt_en',
        'excerpt_lo',
        'body_en',
        'body_lo',
        'cover_image',
        'author_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function localizedTitle(): string
    {
        return app()->getLocale() === 'lo' ? $this->title_lo : $this->title_en;
    }

    public function localizedExcerpt(): ?string
    {
        return app()->getLocale() === 'lo' ? $this->excerpt_lo : $this->excerpt_en;
    }

    public function localizedBody(): ?string
    {
        return app()->getLocale() === 'lo' ? $this->body_lo : $this->body_en;
    }
}
