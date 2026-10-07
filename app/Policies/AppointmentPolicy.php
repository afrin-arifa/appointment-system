<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function view(User $user, Appointment $appointment): bool
    {
        return $appointment->patient_id === $user->id
            || $appointment->doctor_id === $user->id
            || $user->role === 'admin';
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        return $user->role === 'patient'
            && $appointment->patient_id === $user->id
            && $appointment->status === 'pending'
            && ($appointment->appointment_date->isToday() || $appointment->appointment_date->isFuture());
    }

    public function updateStatus(User $user, Appointment $appointment): bool
    {
        return $user->role === 'doctor' && $appointment->doctor_id === $user->id;
    }
}
