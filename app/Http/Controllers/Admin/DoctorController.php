<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDoctorRequest;
use App\Http\Requests\Admin\UpdateDoctorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = User::query()
            ->where('role', 'doctor')
            ->with('doctorProfile')
            ->when($request->string('search')->trim()->value(), function ($query, string $search) {
                $query->where(fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('doctorProfile', fn ($profile) => $profile->where('specialization', 'like', "%{$search}%")));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(StoreDoctorRequest $request)
    {
        $validated = $request->validated();

        $doctor = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'doctor',
        ]);

        $doctor->doctorProfile()->create([
            'specialization' => $validated['specialization'],
            'phone' => $validated['phone'],
            'experience' => $validated['experience'],
            'consultation_fee' => $validated['consultation_fee'],
            'chamber_address' => $validated['chamber_address'],
        ]);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    public function edit(User $doctor)
    {
        abort_if($doctor->role !== 'doctor', 404);

        $doctor->load('doctorProfile');

        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(UpdateDoctorRequest $request, User $doctor)
    {
        abort_if($doctor->role !== 'doctor', 404);

        $validated = $request->validated();

        $doctor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $doctor->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        $doctor->doctorProfile()->updateOrCreate(
            [
                'user_id' => $doctor->id,
            ],
            [
                'specialization' => $validated['specialization'],
                'phone' => $validated['phone'],
                'experience' => $validated['experience'],
                'consultation_fee' => $validated['consultation_fee'],
                'chamber_address' => $validated['chamber_address'],
            ]
        );

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(User $doctor)
    {
        abort_if($doctor->role !== 'doctor', 404);

        if ($doctor->doctorAppointments()->exists()) {
            return back()->with('error', 'A doctor with appointments cannot be deleted. Keep the record to preserve appointment history.');
        }

        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }
}
