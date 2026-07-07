<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnershipInquiry extends Model
{
    protected $fillable = [
        'organization_name',
        'contact_name',
        'contact_email',
        'contact_phone',
        'inquiry_type',
        'message',
        'status',
        'staff_notes',
    ];
}
