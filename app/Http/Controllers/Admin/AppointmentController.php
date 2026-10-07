<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppointmentRequest;
use App\Http\Requests\Admin\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\User;
use App\Notifications\AppointmentStatusUpdated;
use App\Services\AppointmentBookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $appointments = Appointment::query()
            ->with(['doctor.doctorProfile', 'patient'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->value()))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('appointment_date', $request->string('date')->value()))
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(15)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create(): View
    {
        $patients = User::where('role', 'patient')->orderBy('name')->get(['id', 'name', 'email']);
        $schedules = DoctorSchedule::with('doctor:id,name')
            ->where('is_available', true)
            ->whereDate('available_date', '>=', today())
            ->orderBy('available_date')
            ->orderBy('start_time')
            ->get();

        return view('admin.appointments.create', compact('patients', 'schedules'));
    }

    public function store(StoreAppointmentRequest $request, AppointmentBookingService $bookingService): RedirectResponse
    {
        $patient = User::findOrFail($request->validated('patient_id'));
        $bookingService->book($patient, $request->validated());

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function edit(Appointment $appointment): View
    {
        $appointment->load(['doctor.doctorProfile', 'patient']);

        return view('admin.appointments.edit', compact('appointment'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validated();
        $statusChanged = $appointment->status !== $validated['status'];
        $appointment->update($validated);

        if ($statusChanged) {
            $appointment->load(['doctor', 'patient']);
            $appointment->patient->notify(new AppointmentStatusUpdated($appointment));
        }

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated successfully.');
    }
}
