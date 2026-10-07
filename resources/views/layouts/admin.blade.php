@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
        <div>
            <h1 class="h2 mb-1">@yield('page-title')</h1>
            @hasSection('page-description')<p class="text-body-secondary mb-0">@yield('page-description')</p>@endif
        </div>
        @yield('page-actions')
    </div>
    @yield('body')
@endsection
