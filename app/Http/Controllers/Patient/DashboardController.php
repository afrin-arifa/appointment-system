<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $appointments = $request->user()->patientAppointments();

        $stats = [
            'upcoming' => (clone $appointments)->whereIn('status', ['pending', 'approved'])->whereDate('appointment_date', '>=', today())->count(),
            'completed' => (clone $appointments)->where('status', 'completed')->count(),
            'cancelled' => (clone $appointments)->where('status', 'cancelled')->count(),
        ];

        return view('patient.dashboard', compact('stats'));
    }
}
