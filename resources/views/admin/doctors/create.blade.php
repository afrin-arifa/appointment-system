@extends('layouts.admin')
@section('title', 'Add Doctor') @section('page-title', 'Add Doctor')
@section('body')
<div class="card shadow-sm border-0"><div class="card-body p-4"><form action="{{ route('admin.doctors.store') }}" method="POST">@csrf @include('admin.doctors.partials.form')<div class="mt-4"><button class="btn btn-primary">Save doctor</button> <a class="btn btn-outline-secondary" href="{{ route('admin.doctors.index') }}">Cancel</a></div></form></div></div>
@endsection
