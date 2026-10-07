<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'doctors' => User::where('role', 'doctor')->count(),
            'patients' => User::where('role', 'patient')->count(),
            'today' => Appointment::whereDate('appointment_date', today())->whereNot('status', 'cancelled')->count(),
            'pending' => Appointment::where('status', 'pending')->count(),
        ];

        $recentAppointments = Appointment::with(['doctor', 'patient'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAppointments'));
    }
}
