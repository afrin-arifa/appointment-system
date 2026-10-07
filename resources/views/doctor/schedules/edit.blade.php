@extends('layouts.app')
@section('title', 'Edit Schedule')
@section('content')
<h1 class="h2 mb-4">Edit availability schedule</h1><div class="card shadow-sm border-0"><div class="card-body p-4"><form action="{{ route('doctor.schedules.update', $schedule) }}" method="POST">@csrf @method('PUT') @include('doctor.schedules.partials.form')<div class="mt-4"><button class="btn btn-primary">Update schedule</button> <a class="btn btn-outline-secondary" href="{{ route('doctor.schedules.index') }}">Cancel</a></div></form></div></div>
@endsection
