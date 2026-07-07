<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceRequestStatusChanged extends Notification
{
    use Queueable;

    public function __construct(private readonly ServiceRequest $serviceRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = ucwords(str_replace('_', ' ', $this->serviceRequest->status));
        $portalUrl = rtrim(config('app.frontend_url'), '/')."/en/portal/requests/{$this->serviceRequest->id}";

        return (new MailMessage)
            ->subject("Service Request {$this->serviceRequest->request_no} — Status Updated")
            ->line("Your service request \"{$this->serviceRequest->title}\" ({$this->serviceRequest->request_no}) status has changed to: {$statusLabel}.")
            ->when($this->serviceRequest->staff_notes, fn ($mail) => $mail->line("Note from CESD: {$this->serviceRequest->staff_notes}"))
            ->action('View Request', $portalUrl)
            ->line('Thank you for using CESD services.');
    }
}
