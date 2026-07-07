<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'contact_email',
        'contact_phone',
        'address_en',
        'address_lo',
        'facebook_url',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
