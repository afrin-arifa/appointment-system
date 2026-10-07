@extends('layouts.app')
@section('title', 'Choose New Password')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 mb-4">Choose a new password</h1>
                    <form class="vstack gap-3" action="{{ route('password.store') }}" method="POST">@csrf<input type="hidden" name="token" value="{{ $token }}">
                        <div><label class="form-label" for="email">Email address</label><input class="form-control" id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus></div>
                        <div><label class="form-label" for="password">New password</label><input class="form-control" id="password" type="password" name="password" required></div>
                        <div><label class="form-label" for="password_confirmation">Confirm password</label><input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required></div><button class="btn btn-primary">Reset password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
