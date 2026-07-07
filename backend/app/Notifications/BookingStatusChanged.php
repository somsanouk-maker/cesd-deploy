<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusChanged extends Notification
{
    use Queueable;

    public function __construct(private readonly Booking $booking, private readonly ?string $note = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = ucwords(str_replace('_', ' ', $this->booking->status));
        $portalUrl = rtrim(config('app.frontend_url'), '/')."/en/portal/bookings";

        return (new MailMessage)
            ->subject("Booking {$this->booking->booking_no} — {$statusLabel}")
            ->line("Your booking for \"{$this->booking->bookableName()}\" ({$this->booking->booking_no}) is now: {$statusLabel}.")
            ->line("Requested time: {$this->booking->start_at->format('d M Y H:i')} — {$this->booking->end_at->format('d M Y H:i')}")
            ->when($this->note, fn ($mail) => $mail->line($this->note))
            ->action('View My Bookings', $portalUrl);
    }
}
