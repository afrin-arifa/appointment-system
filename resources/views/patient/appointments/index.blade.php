@extends('layouts.app')
@section('title', 'My Appointments')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">My appointments</h1><a class="btn btn-primary" href="{{ route('patient.doctors.index') }}">Book appointment</a>
    </div>
    <form class="row g-2 mb-3">
        <div class="col-sm-3"><select class="form-select" name="status">
                <option value="">All statuses</option>
                @foreach (['pending', 'approved', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto"><button class="btn btn-outline-primary">Filter</button></div>
    </form>
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date & time</th>
                        <th>Doctor</th>
                        <th>Symptoms</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->appointment_date->format('d M Y') }}<div class="small text-body-secondary">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                            </td>
                            <td>Dr. {{ $appointment->doctor->name }}<div class="small text-body-secondary">{{ $appointment->doctor->doctorProfile?->specialization }}</div>
                            </td>
                            <td>{{ $appointment->symptoms ?: '—' }}</td>
                            <td><span class="badge text-bg-secondary text-capitalize">{{ $appointment->status }}</span></td>
                            <td class="text-end">
                                @can('cancel', $appointment)
                                    <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" onsubmit="return confirm('Cancel this appointment?');">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-danger">Cancel</button></form>
                                @endcan
                            </td>
                    </tr>@empty<tr>
                            <td colspan="5" class="text-center py-4 text-body-secondary">You have no appointments yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $appointments->links() }}</div>
@endsection
