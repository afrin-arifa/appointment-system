<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\User;
use App\Notifications\AppointmentBooked;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentBookingService
{
    /** @return array<int, string> */
    public function availableSlots(DoctorSchedule $schedule): array
    {
        if (! $schedule->is_available || $schedule->available_date->isBefore(today())) {
            return [];
        }

        $bookedSlots = $schedule->appointments()
            ->whereNotIn('status', ['cancelled'])
            ->pluck('appointment_time')
            ->map(fn ($time) => Carbon::parse($time)->format('H:i'))
            ->all();

        $timezone = config('app.timezone');
        $start = Carbon::parse($schedule->available_date->format('Y-m-d').' '.$schedule->start_time, $timezone);
        $end = Carbon::parse($schedule->available_date->format('Y-m-d').' '.$schedule->end_time, $timezone);
        $now = now($timezone);
        $slots = [];

        while ($start->copy()->addMinutes($schedule->appointment_duration)->lte($end)) {
            $slot = $start->format('H:i');

            if (! in_array($slot, $bookedSlots, true) && $start->greaterThan($now)) {
                $slots[] = $slot;
            }

            $start->addMinutes($schedule->appointment_duration);
        }

        return $slots;
    }

    public function book(User $patient, array $attributes): Appointment
    {
        try {
            return DB::transaction(function () use ($patient, $attributes) {
                $schedule = DoctorSchedule::query()
                    ->lockForUpdate()
                    ->findOrFail($attributes['doctor_schedule_id']);

                if (
                    ! $schedule->is_available
                    || $schedule->doctor_id !== (int) $attributes['doctor_id']
                    || $schedule->available_date->format('Y-m-d') !== $attributes['appointment_date']
                    || ! in_array($attributes['appointment_time'], $this->availableSlots($schedule), true)
                ) {
                    throw ValidationException::withMessages([
                        'appointment_time' => 'This appointment slot is no longer available. Please choose another slot.',
                    ]);
                }

                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $schedule->doctor_id,
                    'doctor_schedule_id' => $schedule->id,
                    'appointment_date' => $schedule->available_date,
                    'appointment_time' => $attributes['appointment_time'],
                    'symptoms' => $attributes['symptoms'] ?? null,
                    'status' => 'pending',
                ]);

                $appointment->load(['doctor', 'patient']);
                $appointment->doctor->notify(new AppointmentBooked($appointment));
                $appointment->patient->notify(new AppointmentBooked($appointment));

                return $appointment;
            });
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                throw ValidationException::withMessages([
                    'appointment_time' => 'This appointment slot has just been booked. Please choose another slot.',
                ]);
            }

            throw $exception;
        }
    }
}
