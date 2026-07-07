<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutContent extends Model
{
    protected $table = 'about_content';

    protected $fillable = [
        'title_en', 'title_lo',
        'background_en', 'background_lo',
        'vision_en', 'vision_lo',
        'mission_en', 'mission_lo',
        'objective1_en', 'objective1_lo',
        'objective2_en', 'objective2_lo',
        'objective3_en', 'objective3_lo',
        'objective4_en', 'objective4_lo',
        'org_director_en', 'org_director_lo',
        'org_deputy_director_en', 'org_deputy_director_lo',
        'org_admin_en', 'org_admin_lo',
        'org_technical_en', 'org_technical_lo',
        'org_innovation_en', 'org_innovation_lo',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
