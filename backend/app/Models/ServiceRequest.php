<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequest extends Model
{
    protected $fillable = [
        'request_no',
        'user_id',
        'service_id',
        'laboratory_id',
        'assigned_staff_id',
        'title',
        'description',
        'sample_information',
        'required_date',
        'contact_name',
        'contact_email',
        'contact_phone',
        'organization',
        'status',
        'staff_notes',
    ];

    protected function casts(): array
    {
        return [
            'required_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }
}
