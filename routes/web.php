<?php

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\ScheduleController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Patient\AppointmentController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->name('password.store');

});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', AdminDashboardController::class)
            ->name('dashboard');

        Route::resource('doctors', DoctorController::class)
            ->except('show');

        Route::get('/patients', [AdminPatientController::class, 'index'])
            ->name('patients.index');
        Route::get('/patients/create', [AdminPatientController::class, 'create'])
            ->name('patients.create');
        Route::post('/patients', [AdminPatientController::class, 'store'])
            ->name('patients.store');
        Route::get('/patients/{patient}/edit', [AdminPatientController::class, 'edit'])
            ->name('patients.edit');
        Route::put('/patients/{patient}', [AdminPatientController::class, 'update'])
            ->name('patients.update');

        Route::get('/appointments', [AdminAppointmentController::class, 'index'])
            ->name('appointments.index');
        Route::get('/appointments/create', [AdminAppointmentController::class, 'create'])
            ->name('appointments.create');
        Route::post('/appointments', [AdminAppointmentController::class, 'store'])
            ->name('appointments.store');
        Route::get('/appointments/{appointment}/edit', [AdminAppointmentController::class, 'edit'])
            ->name('appointments.edit');
        Route::put('/appointments/{appointment}', [AdminAppointmentController::class, 'update'])
            ->name('appointments.update');

    });

Route::middleware(['auth', 'role:doctor'])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {

        Route::get('/dashboard', DoctorDashboardController::class)
            ->name('dashboard');

        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::patch('/appointments/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus'])
            ->name('appointments.status.update');

        Route::get('/schedules', [ScheduleController::class, 'index'])
            ->name('schedules.index');

        Route::get('/schedules/create', [ScheduleController::class, 'create'])
            ->name('schedules.create');

        Route::post('/schedules', [ScheduleController::class, 'store'])
            ->name('schedules.store');

        Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])
            ->name('schedules.edit');

        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])
            ->name('schedules.update');

        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])
            ->name('schedules.destroy');

    });

Route::middleware(['auth', 'role:patient'])
    ->prefix('patient')
    ->name('patient.')
    ->group(function () {

        Route::get('/dashboard', PatientDashboardController::class)
            ->name('dashboard');

        Route::get('/doctors', [AppointmentController::class, 'doctors'])
            ->name('doctors.index');

        Route::get('/doctors/{doctor}', [AppointmentController::class, 'showDoctor'])
            ->name('doctors.show');

        Route::post('/appointments', [AppointmentController::class, 'store'])
            ->name('appointments.store');

        Route::get('/appointments', [AppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
            ->name('appointments.cancel');

    });

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
