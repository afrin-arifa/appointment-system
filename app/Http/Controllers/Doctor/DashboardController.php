<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $appointments = $request->user()->doctorAppointments();

        $stats = [
            'today' => (clone $appointments)->whereDate('appointment_date', today())->whereNot('status', 'cancelled')->count(),
            'upcoming' => (clone $appointments)->whereIn('status', ['pending', 'approved'])->whereDate('appointment_date', '>=', today())->count(),
            'completed' => (clone $appointments)->where('status', 'completed')->count(),
            'patients' => (clone $appointments)->distinct('patient_id')->count('patient_id'),
        ];

        return view('doctor.dashboard', compact('stats'));
    }
}
