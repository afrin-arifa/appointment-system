@extends('layouts.app')
@section('title', 'Log in | Doctor Appointment')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 text-center mb-2">Welcome back</h1>
                    <p class="text-center text-body-secondary mb-4">Log in to manage your appointments.</p>
                    <form action="{{ route('login') }}" method="POST" class="vstack gap-3">@csrf
                        <div><label class="form-label" for="email">Email address</label><input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus></div>
                        <div>
                            <div class="d-flex justify-content-between"><label class="form-label" for="password">Password</label><a class="small" href="{{ route('password.request') }}">Forgot password?</a></div><input class="form-control" id="password" type="password" name="password" required>
                        </div>
                        <div class="form-check"><input class="form-check-input" id="remember" type="checkbox" name="remember"><label class="form-check-label" for="remember">Remember me</label></div>
                        <button class="btn btn-primary w-100" type="submit">Log in</button>
                    </form>
                    <p class="text-center mt-4 mb-0">New here? <a href="{{ route('register') }}">Create an account</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
