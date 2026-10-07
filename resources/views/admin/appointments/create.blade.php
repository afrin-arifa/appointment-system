@extends('layouts.admin')
@section('title', 'Add Appointment') @section('page-title', 'Add Appointment')
@section('body')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.appointments.store') }}" method="POST" class="vstack gap-3">@csrf
                <div><label class="form-label" for="patient_id">Patient</label><select class="form-select" id="patient_id" name="patient_id" required>
                        <option value="">Choose a patient</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->name }} — {{ $patient->email }}</option>
                        @endforeach
                    </select></div>
                <div>
                    <label class="form-label" for="slot">Available slot</label>
                    <select class="form-select" id="slot" name="slot" required>
                        <option value="">Choose a date, doctor, and time</option>
                        @foreach ($schedules as $schedule)
                            @foreach (app(\App\Services\AppointmentBookingService::class)->availableSlots($schedule) as $slot)
                                <option value="{{ $schedule->id }}|{{ $slot }}" data-schedule="{{ $schedule->id }}" data-doctor="{{ $schedule->doctor_id }}" data-date="{{ $schedule->available_date->format('Y-m-d') }}" data-time="{{ $slot }}">
                                    {{ $schedule->available_date->format('d M Y') }} — Dr. {{ $schedule->doctor->name }} — {{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('h:i A') }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <div class="form-text">Only currently available slots are displayed.</div>
                </div>
                <div><label class="form-label" for="symptoms">Symptoms or reason <span class="text-body-secondary">(optional)</span></label>
                    <textarea class="form-control" id="symptoms" name="symptoms" rows="3" maxlength="1000">{{ old('symptoms') }}</textarea>
                </div>
                <div><button class="btn btn-primary">Create appointment</button> <a class="btn btn-outline-secondary" href="{{ route('admin.appointments.index') }}">Cancel</a></div>
            </form>
        </div>
    </div>
@endsection
