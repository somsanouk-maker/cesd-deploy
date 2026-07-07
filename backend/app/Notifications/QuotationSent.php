<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotationSent extends Notification
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
        $amount = number_format((float) $this->serviceRequest->quoted_amount, 2);
        $portalUrl = rtrim(config('app.frontend_url'), '/')."/en/portal/requests/{$this->serviceRequest->id}";

        return (new MailMessage)
            ->subject("Quotation Ready for Request {$this->serviceRequest->request_no}")
            ->line("CESD has prepared a quotation for your service request \"{$this->serviceRequest->title}\" ({$this->serviceRequest->request_no}).")
            ->line("Quoted amount: LAK {$amount}")
            ->when($this->serviceRequest->quotation_notes, fn ($mail) => $mail->line($this->serviceRequest->quotation_notes))
            ->action('Review & Respond', $portalUrl)
            ->line('Please accept or decline this quotation from your CESD portal.');
    }
}
