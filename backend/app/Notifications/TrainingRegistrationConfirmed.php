<?php

namespace App\Notifications;

use App\Models\TrainingRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrainingRegistrationConfirmed extends Notification
{
    use Queueable;

    public function __construct(private readonly TrainingRegistration $registration)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $course = $this->registration->trainingCourse;
        $waitlisted = $this->registration->status === 'waitlisted';

        $mail = (new MailMessage)
            ->subject($waitlisted ? "Waitlisted: {$course->localizedTitle()}" : "Registration Confirmed: {$course->localizedTitle()}")
            ->line($waitlisted
                ? "The course \"{$course->localizedTitle()}\" is currently full. You've been added to the waitlist and will be notified if a spot opens up."
                : "You're registered for \"{$course->localizedTitle()}\".");

        if ($course->start_date) {
            $mail->line("Starts: {$course->start_date->format('d M Y')}");
        }

        return $mail->line('Thank you for your interest in CESD training.');
    }
}
