@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="row justify-content-center"><div class="col-md-8 col-lg-5"><div class="card shadow-sm border-0"><div class="card-body p-4 p-md-5"><h1 class="h3 mb-2">Reset your password</h1><p class="text-body-secondary">Enter your email and we will send a reset link.</p><form class="vstack gap-3" action="{{ route('password.email') }}" method="POST">@csrf<div><label class="form-label" for="email">Email address</label><input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus></div><button class="btn btn-primary">Email reset link</button></form><p class="mt-3 mb-0"><a href="{{ route('login') }}">Back to log in</a></p></div></div></div></div>
@endsection
