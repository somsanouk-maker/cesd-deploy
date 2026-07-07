<?php

namespace App\Models;

use App\Notifications\QuotationSent;
use App\Notifications\ServiceRequestStatusChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class ServiceRequest extends Model
{
    use Notifiable;

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
        'quotation_status',
        'quoted_amount',
        'quotation_notes',
        'quoted_by',
        'quoted_at',
        'staff_notes',
    ];

    protected function casts(): array
    {
        return [
            'required_date' => 'date',
            'quoted_amount' => 'decimal:2',
            'quoted_at' => 'datetime',
        ];
    }

    /**
     * Notifications go to contact_email so guest submissions still receive
     * status/quotation updates even without a user account.
     */
    public function routeNotificationForMail(): string
    {
        return $this->contact_email;
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

    public function quotedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'quoted_by');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ServiceRequestStatusLog::class)->latest('created_at');
    }

    public function updateStatus(string $status, ?User $actor = null, ?string $note = null): void
    {
        $from = $this->status;
        $this->update(['status' => $status]);

        $this->statusLogs()->create([
            'field' => 'status',
            'from_value' => $from,
            'to_value' => $status,
            'note' => $note,
            'changed_by' => $actor?->id,
        ]);

        $this->notify(new ServiceRequestStatusChanged($this));
    }

    public function setQuotation(float $amount, ?string $notes, User $staff): void
    {
        $from = $this->quotation_status;

        $this->update([
            'quotation_status' => 'quoted',
            'quoted_amount' => $amount,
            'quotation_notes' => $notes,
            'quoted_by' => $staff->id,
            'quoted_at' => now(),
        ]);

        $this->statusLogs()->create([
            'field' => 'quotation_status',
            'from_value' => $from,
            'to_value' => 'quoted',
            'note' => $notes,
            'changed_by' => $staff->id,
        ]);

        $this->notify(new QuotationSent($this));
    }

    public function respondToQuotation(bool $accepted, ?User $customer = null): void
    {
        $from = $this->quotation_status;
        $newStatus = $accepted ? 'accepted' : 'declined';

        $this->update(['quotation_status' => $newStatus]);

        $this->statusLogs()->create([
            'field' => 'quotation_status',
            'from_value' => $from,
            'to_value' => $newStatus,
            'changed_by' => $customer?->id,
        ]);
    }
}
