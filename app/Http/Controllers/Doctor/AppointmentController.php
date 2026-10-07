<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateAppointmentStatusRequest;
use App\Models\Appointment;
use App\Notifications\AppointmentStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $appointments = $request->user()
            ->doctorAppointments()
            ->with('patient')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->value()))
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(10)
            ->withQueryString();

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('updateStatus', $appointment);

        $status = $request->validated('status');

        if ($appointment->status === 'completed' || $appointment->status === 'cancelled') {
            return back()->with('error', 'Completed or cancelled appointments cannot be changed.');
        }

        if ($status === 'completed' && $appointment->appointment_date->isFuture()) {
            return back()->with('error', 'An appointment cannot be marked complete before its scheduled date.');
        }

        $appointment->update(['status' => $status]);
        $appointment->load(['doctor', 'patient']);
        $appointment->patient->notify(new AppointmentStatusUpdated($appointment));

        return back()->with('success', 'Appointment status updated.');
    }
}
