@extends('layouts.app')
@section('title', 'Patient Dashboard')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-body-secondary mb-0">Manage appointments and find the right doctor.</p>
        </div><a class="btn btn-primary" href="{{ route('patient.doctors.index') }}">Find doctors</a>
    </div>
    <div class="row g-3 mb-4">
        @foreach (['Upcoming' => ['value' => $stats['upcoming'], 'class' => 'primary'], 'Completed' => ['value' => $stats['completed'], 'class' => 'success'], 'Cancelled' => ['value' => $stats['cancelled'], 'class' => 'secondary']] as $label => $stat)
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-body-secondary mb-2">{{ $label }} appointments</p>
                        <p class="display-6 text-{{ $stat['class'] }} fw-semibold mb-0">{{ $stat['value'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h2 class="h4">Need an appointment?</h2>
            <p class="text-body-secondary">Browse doctors by name or specialization, then select a currently available time slot.</p><a class="btn btn-outline-primary" href="{{ route('patient.doctors.index') }}">Book an appointment</a> <a class="btn btn-outline-secondary" href="{{ route('patient.appointments.index') }}">View my appointments</a>
        </div>
    </div>
@endsection
