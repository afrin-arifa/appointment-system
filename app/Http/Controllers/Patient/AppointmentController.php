<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\User;
use App\Services\AppointmentBookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function doctors(Request $request): View
    {
        $doctors = User::query()
            ->with('doctorProfile')
            ->where('role', 'doctor')
            ->when($request->string('search')->trim()->value(), function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('doctorProfile', fn ($profile) => $profile->where('specialization', 'like', "%{$search}%"));
                });
            })
            ->when($request->string('specialization')->trim()->value(), fn ($query, string $specialization) => $query
                ->whereHas('doctorProfile', fn ($profile) => $profile->where('specialization', $specialization)))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $specializations = User::query()
            ->where('role', 'doctor')
            ->whereHas('doctorProfile')
            ->with('doctorProfile:id,user_id,specialization')
            ->get()
            ->pluck('doctorProfile.specialization')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('patient.doctors.index', compact('doctors', 'specializations'));
    }

    public function showDoctor(User $doctor, AppointmentBookingService $bookingService): View
    {
        abort_if($doctor->role !== 'doctor', 404);

        $doctor->load('doctorProfile');

        $schedules = DoctorSchedule::query()
            ->with('appointments:id,doctor_schedule_id,appointment_time,status')
            ->where('doctor_id', $doctor->id)
            ->where('is_available', true)
            ->whereDate('available_date', '>=', today())
            ->orderBy('available_date')
            ->orderBy('start_time')
            ->get();

        $slotsBySchedule = $schedules->mapWithKeys(fn (DoctorSchedule $schedule) => [
            $schedule->id => $bookingService->availableSlots($schedule),
        ]);

        return view('patient.doctors.show', compact(
            'doctor',
            'schedules',
            'slotsBySchedule'
        ));
    }

    public function store(StoreAppointmentRequest $request, AppointmentBookingService $bookingService): RedirectResponse
    {
        $bookingService->book($request->user(), $request->validated());

        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Your appointment request has been submitted.');
    }

    public function index(Request $request): View
    {
        $appointments = $request->user()
            ->patientAppointments()
            ->with(['doctor.doctorProfile', 'schedule'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->value()))
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(10)
            ->withQueryString();

        return view('patient.appointments.index', compact('appointments'));
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('cancel', $appointment);

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Your appointment has been cancelled.');
    }
}
