@extends('layouts.app')
@section('title', 'Register | Doctor Appointment')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 text-center mb-2">Create your account</h1>
                    <p class="text-center text-body-secondary mb-4">Register to book doctor appointments.</p>
                    <form action="{{ route('register') }}" method="POST" class="vstack gap-3">@csrf
                        <div><label class="form-label" for="name">Full name</label><input class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus></div>
                        <div><label class="form-label" for="email">Email address</label><input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required></div>
                        <div><label class="form-label" for="password">Password</label><input class="form-control" id="password" type="password" name="password" required></div>
                        <div><label class="form-label" for="password_confirmation">Confirm password</label><input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required></div>
                        <button class="btn btn-primary w-100" type="submit">Create account</button>
                    </form>
                    <p class="text-center mt-4 mb-0">Already registered? <a href="{{ route('login') }}">Log in</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
