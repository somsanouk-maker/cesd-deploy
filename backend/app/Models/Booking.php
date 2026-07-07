<?php

namespace App\Models;

use App\Notifications\BookingStatusChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    protected $fillable = [
        'booking_no',
        'user_id',
        'bookable_id',
        'bookable_type',
        'purpose',
        'start_at',
        'end_at',
        'status',
        'requires_advisor_approval',
        'advisor_id',
        'advisor_decided_at',
        'advisor_note',
        'staff_id',
        'staff_decided_at',
        'staff_note',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'requires_advisor_approval' => 'boolean',
            'advisor_decided_at' => 'datetime',
            'staff_decided_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function bookableName(): string
    {
        $bookable = $this->bookable;

        if (! $bookable) {
            return '—';
        }

        return method_exists($bookable, 'localizedName') ? $bookable->localizedName() : $bookable->name_en;
    }

    public function approveByAdvisor(User $advisor, ?string $note = null): void
    {
        $this->update([
            'status' => 'pending_staff',
            'advisor_id' => $advisor->id,
            'advisor_decided_at' => now(),
            'advisor_note' => $note,
        ]);

        $this->notifyOwner('Your advisor approved this booking. It now awaits final approval from CESD staff.');
    }

    public function approveByStaff(User $staff, ?string $note = null): void
    {
        $this->update([
            'status' => 'approved',
            'staff_id' => $staff->id,
            'staff_decided_at' => now(),
            'staff_note' => $note,
        ]);

        $this->notifyOwner($note);
    }

    public function reject(User $actor, ?string $note, string $stage): void
    {
        $attributes = ['status' => 'rejected'];

        if ($stage === 'advisor') {
            $attributes['advisor_id'] = $actor->id;
            $attributes['advisor_decided_at'] = now();
            $attributes['advisor_note'] = $note;
        } else {
            $attributes['staff_id'] = $actor->id;
            $attributes['staff_decided_at'] = now();
            $attributes['staff_note'] = $note;
        }

        $this->update($attributes);

        $this->notifyOwner($note);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    private function notifyOwner(?string $note = null): void
    {
        $owner = $this->user;

        if ($owner && $owner->email) {
            $owner->notify(new BookingStatusChanged($this, $note));
        }
    }
}
