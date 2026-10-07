@extends('layouts.admin')
@section('title', 'Doctors') @section('page-title', 'Doctors') @section('page-actions')<a class="btn btn-primary" href="{{ route('admin.doctors.create') }}">
Add doctor</a>@endsection
@section('body')
<form class="row g-2 mb-3">
    <div class="col-md-5"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search name, email, or specialization"></div>
    <div class="col-auto"><button class="btn btn-outline-primary">Search</button></div>
</form>
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Phone</th>
                    <th>Fee</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->name }}<div class="small text-body-secondary">{{ $doctor->email }}</div>
                        </td>
                        <td>{{ $doctor->doctorProfile?->specialization }}</td>
                        <td>{{ $doctor->doctorProfile?->phone }}</td>
                        <td>৳{{ number_format($doctor->doctorProfile?->consultation_fee ?? 0, 2) }}</td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.doctors.edit', $doctor) }}">Edit</a>
                            <form class="d-inline" action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" onsubmit="return confirm('Delete this doctor?');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                        </td>
                </tr>@empty<tr>
                        <td colspan="5" class="text-center py-4 text-body-secondary">No doctors have been added.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $doctors->links() }}</div>
@endsection
