<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'patient_id' => User::factory()->state(['role' => 'patient']),
            'doctor_id' => User::factory()->state(['role' => 'doctor']),
            'doctor_schedule_id' => DoctorSchedule::factory(),
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '09:00',
            'symptoms' => fake()->sentence(),
            'status' => 'pending',
        ];
    }
}
