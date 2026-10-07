@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'An overview of appointments and users.')
@section('body')
<div class="row g-3 mb-4">@foreach (['Doctors' => $stats['doctors'], 'Patients' => $stats['patients'], "Today's appointments" => $stats['today'], 'Pending requests' => $stats['pending']] as $label => $value)<div class="col-sm-6 col-xl-3"><div class="card h-100 shadow-sm border-0"><div class="card-body"><p class="text-body-secondary mb-2">{{ $label }}</p><p class="display-6 fw-semibold mb-0">{{ $value }}</p></div></div></div>@endforeach</div>
<div class="card shadow-sm border-0"><div class="card-header bg-white"><h2 class="h5 mb-0">Recent appointments</h2></div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Status</th></tr></thead><tbody>@forelse($recentAppointments as $appointment)<tr><td>{{ $appointment->patient->name }}</td><td>Dr. {{ $appointment->doctor->name }}</td><td>{{ $appointment->appointment_date->format('d M Y') }}</td><td><span class="badge text-bg-secondary text-capitalize">{{ $appointment->status }}</span></td></tr>@empty<tr><td colspan="4" class="text-center py-4 text-body-secondary">No appointments yet.</td></tr>@endforelse</tbody></table></div></div>
@endsection
