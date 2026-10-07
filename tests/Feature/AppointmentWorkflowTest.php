<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_book_an_available_slot(): void
    {
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = User::factory()->create(['role' => 'doctor']);
        DoctorProfile::factory()->for($doctor)->create();
        $schedule = DoctorSchedule::factory()->for($doctor, 'doctor')->create([
            'available_date' => now()->addDay()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'appointment_duration' => 30,
        ]);

        $response = $this->actingAs($patient)->post(route('patient.appointments.store'), [
            'doctor_id' => $doctor->id,
            'doctor_schedule_id' => $schedule->id,
            'appointment_date' => $schedule->available_date->format('Y-m-d'),
            'appointment_time' => '09:00',
            'symptoms' => 'Routine check-up',
        ]);

        $response->assertRedirect(route('patient.appointments.index'));
        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'doctor_schedule_id' => $schedule->id,
            'appointment_time' => '09:00',
            'status' => 'pending',
        ]);
    }

    public function test_patient_cannot_book_an_already_taken_slot(): void
    {
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = User::factory()->create(['role' => 'doctor']);
        $schedule = DoctorSchedule::factory()->for($doctor, 'doctor')->create([
            'available_date' => now()->addDay()->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);
        Appointment::factory()->for($patient, 'patient')->for($doctor, 'doctor')->for($schedule, 'schedule')->create([
            'appointment_date' => $schedule->available_date,
            'appointment_time' => '09:00',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($patient)->from(route('patient.doctors.show', $doctor))->post(route('patient.appointments.store'), [
            'doctor_id' => $doctor->id,
            'doctor_schedule_id' => $schedule->id,
            'appointment_date' => $schedule->available_date->format('Y-m-d'),
            'appointment_time' => '09:00',
        ]);

        $response->assertRedirect(route('patient.doctors.show', $doctor));
        $response->assertSessionHasErrors('appointment_time');
        $this->assertSame(1, Appointment::count());
    }

    public function test_patient_cannot_cancel_another_patients_appointment(): void
    {
        $owner = User::factory()->create(['role' => 'patient']);
        $otherPatient = User::factory()->create(['role' => 'patient']);
        $doctor = User::factory()->create(['role' => 'doctor']);
        $schedule = DoctorSchedule::factory()->for($doctor, 'doctor')->create(['available_date' => now()->addDay()->toDateString()]);
        $appointment = Appointment::factory()->for($owner, 'patient')->for($doctor, 'doctor')->for($schedule, 'schedule')->create(['appointment_date' => $schedule->available_date]);

        $this->actingAs($otherPatient)
            ->patch(route('patient.appointments.cancel', $appointment))
            ->assertForbidden();
    }
}
