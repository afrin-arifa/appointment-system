<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Doctor Appointment System')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>

    <body class="bg-light min-vh-100">
        @auth
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
                <div class="container">
                    <a class="navbar-brand fw-semibold" href="{{ route(auth()->user()->role . '.dashboard') }}">Doctor Appointment</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="mainNavigation">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            @if (auth()->user()->role === 'admin')
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.doctors.index') }}">Doctors</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.patients.index') }}">Patients</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.appointments.index') }}">Appointments</a></li>
                            @elseif (auth()->user()->role === 'doctor')
                                <li class="nav-item"><a class="nav-link" href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('doctor.schedules.index') }}">Schedules</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('doctor.appointments.index') }}">Appointments</a></li>
                            @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('patient.dashboard') }}">Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('patient.doctors.index') }}">Find Doctors</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('patient.appointments.index') }}">My Appointments</a></li>
                            @endif
                        </ul>
                        <div class="d-flex align-items-center gap-3 text-white">
                            <span class="small">{{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST">@csrf <button class="btn btn-sm btn-outline-light">Log out</button></form>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth

        <main class="container py-4 py-md-5">
            <x-alert />
            @yield('content')
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>

</html>
