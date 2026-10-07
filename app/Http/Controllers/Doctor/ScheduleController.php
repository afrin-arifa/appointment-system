<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreScheduleRequest;
use App\Models\DoctorSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = auth()
            ->user()
            ->doctorSchedules()
            ->orderBy('available_date')
            ->orderBy('start_time')
            ->get();

        return view('doctor.schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        return view('doctor.schedules.create');
    }

    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->ensureNoOverlap($validated);

        DoctorSchedule::create([
            'doctor_id' => auth()->id(),
            'available_date' => $validated['available_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'appointment_duration' => $validated['appointment_duration'],
            'is_available' => $validated['is_available'] ?? true,
        ]);

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule added successfully.');
    }

    public function edit(DoctorSchedule $schedule): View
    {
        $this->authorize('update', $schedule);

        return view('doctor.schedules.edit', compact('schedule'));
    }

    public function update(StoreScheduleRequest $request, DoctorSchedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        if ($schedule->appointments()->exists()) {
            return back()->with('error', 'A schedule with appointments cannot be changed. Cancel or complete its appointments first.');
        }

        $validated = $request->validated();

        $this->ensureNoOverlap($validated, $schedule);

        $schedule->update([
            'available_date' => $validated['available_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'appointment_duration' => $validated['appointment_duration'],
            'is_available' => $validated['is_available'] ?? $schedule->is_available,
        ]);

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(DoctorSchedule $schedule): RedirectResponse
    {
        $this->authorize('delete', $schedule);

        if ($schedule->appointments()->exists()) {
            return back()->with('error', 'A schedule with appointments cannot be deleted.');
        }

        $schedule->delete();

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }

    private function ensureNoOverlap(array $validated, ?DoctorSchedule $ignore = null): void
    {
        $overlaps = auth()->user()->doctorSchedules()
            ->whereDate('available_date', $validated['available_date'])
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore))
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time'])
            ->exists();

        if ($overlaps) {
            throw ValidationException::withMessages([
                'start_time' => 'This time range overlaps with an existing schedule.',
            ]);
        }
    }
}
