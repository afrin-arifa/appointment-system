<?php

namespace App\Notifications;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Appointment $appointment)
    {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Appointment status updated')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your appointment with Dr. '.$this->appointment->doctor->name.' is now '.strtolower($this->appointment->status).'.')
            ->line('Date: '.$this->appointment->appointment_date->format('d M Y'))
            ->line('Time: '.Carbon::parse($this->appointment->appointment_time)->format('h:i A'));
    }
}
