@extends('layouts.app')
@section('title', 'Add Schedule')
@section('content')
<h1 class="h2 mb-4">Add availability schedule</h1><div class="card shadow-sm border-0"><div class="card-body p-4"><form action="{{ route('doctor.schedules.store') }}" method="POST">@csrf @include('doctor.schedules.partials.form')<div class="mt-4"><button class="btn btn-primary">Save schedule</button> <a class="btn btn-outline-secondary" href="{{ route('doctor.schedules.index') }}">Cancel</a></div></form></div></div>
@endsection
