<?php

namespace App\Policies;

use App\Models\DoctorSchedule;
use App\Models\User;

class DoctorSchedulePolicy
{
    public function update(User $user, DoctorSchedule $schedule): bool
    {
        return $user->role === 'doctor' && $schedule->doctor_id === $user->id;
    }

    public function delete(User $user, DoctorSchedule $schedule): bool
    {
        return $this->update($user, $schedule);
    }
}
