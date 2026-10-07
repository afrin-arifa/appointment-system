<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePatientRequest;
use App\Http\Requests\Admin\UpdatePatientRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $patients = User::query()
            ->where('role', 'patient')
            ->when($request->string('search')->trim()->value(), function ($query, string $search) {
                $query->where(fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            })
            ->withCount('patientAppointments')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.patients.index', compact('patients'));
    }

    public function create(): View
    {
        return view('admin.patients.create');
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'patient',
        ]);

        return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully.');
    }

    public function edit(User $patient): View
    {
        abort_if($patient->role !== 'patient', 404);

        return view('admin.patients.edit', compact('patient'));
    }

    public function update(UpdatePatientRequest $request, User $patient): RedirectResponse
    {
        abort_if($patient->role !== 'patient', 404);

        $validated = $request->validated();
        $patient->update(['name' => $validated['name'], 'email' => $validated['email']]);

        if (filled($validated['password'] ?? null)) {
            $patient->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully.');
    }
}
