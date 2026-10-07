<?php

namespace App\Http\Requests\Admin;

use App\Models\DoctorSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'slot' => ['required', 'regex:/^\d+\|\d{2}:\d{2}$/'],
            'patient_id' => ['required', Rule::exists('users', 'id')->where('role', 'patient')],
            'doctor_id' => ['required', Rule::exists('users', 'id')->where('role', 'doctor')],
            'doctor_schedule_id' => ['required', 'exists:doctor_schedules,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'symptoms' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $slot = (string) $this->input('slot');

        if (! preg_match('/^(\d+)\|(\d{2}:\d{2})$/', $slot, $matches)) {
            return;
        }

        $schedule = DoctorSchedule::find($matches[1]);

        if (! $schedule) {
            return;
        }

        $this->merge([
            'doctor_id' => $schedule->doctor_id,
            'doctor_schedule_id' => $schedule->id,
            'appointment_date' => $schedule->available_date->format('Y-m-d'),
            'appointment_time' => $matches[2],
        ]);
    }
}
