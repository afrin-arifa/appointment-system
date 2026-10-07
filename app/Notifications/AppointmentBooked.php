<?php

namespace App\Notifications;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentBooked extends Notification implements ShouldQueue
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
        $isDoctor = $notifiable->id === $this->appointment->doctor_id;
        $counterpart = $isDoctor ? $this->appointment->patient->name : 'Dr. '.$this->appointment->doctor->name;

        return (new MailMessage)
            ->subject($isDoctor ? 'New appointment request' : 'Appointment request received')
            ->greeting('Hello '.$notifiable->name.',')
            ->line($isDoctor ? "You have a new appointment request from {$counterpart}." : "Your appointment request with {$counterpart} has been received.")
            ->line('Date: '.$this->appointment->appointment_date->format('d M Y'))
            ->line('Time: '.Carbon::parse($this->appointment->appointment_time)->format('h:i A'));
    }
}
