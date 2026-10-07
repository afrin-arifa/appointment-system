@extends('layouts.admin')
@section('title', 'Add Patient') @section('page-title', 'Add Patient')
@section('body')<div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.patients.store') }}" method="POST">@csrf @include('admin.patients.partials.form')<div class="mt-4"><button class="btn btn-primary">Save patient</button> <a class="btn btn-outline-secondary" href="{{ route('admin.patients.index') }}">Cancel</a></div>
            </form>
        </div>
</div>@endsection
