@extends('layouts.admin')
@section('title', 'Edit Patient') @section('page-title', 'Edit Patient')
@section('body')<div class="card shadow-sm border-0"><div class="card-body p-4"><form action="{{ route('admin.patients.update', $patient) }}" method="POST">@csrf @method('PUT') @include('admin.patients.partials.form')<div class="mt-4"><button class="btn btn-primary">Update patient</button> <a class="btn btn-outline-secondary" href="{{ route('admin.patients.index') }}">Cancel</a></div></form></div></div>@endsection
