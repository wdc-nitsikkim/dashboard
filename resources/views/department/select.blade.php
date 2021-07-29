@extends('layouts.admin')

@section('content')
@if (Auth::user()->can('create', \App\Models\Department::class))
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <div class="dropdown">
                <button class="btn btn-secondary d-inline-flex align-items-center me-2 dropdown-toggle"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="material-icons mx-1">add</span>
                    New
                </button>
                <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                    <a class="dropdown-item d-flex align-items-center"
                        href="">
                        <span class="material-icons">add_circle</span>
                        Create New Department
                    </a>
                    @if (Auth::user()->can('update', \App\Models\Department::class))
                        <a class="dropdown-item d-flex align-items-center"
                            href="">
                            <span class="material-icons">edit</span>
                            Edit Existing
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<div class="my-3">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">
                Select a department to proceed
            </h1>
            {{-- <p class="mb-0"></p> --}}
        </div>
        <div>
            <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <span class="material-icons mx-1">help</span>
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <h5>Additional Access</h5>
        <div class="mb-3">
            @if ($preferred->count() > 0)
                @foreach ($preferred as $dept)
                    <a class="btn btn-lg btn-outline-tertiary mx-1 mb-2"
                        href="{{ route('department.saveInSession', $dept['code']) }}" spoof spoof-method="POST">
                        {{ $dept['name'] }}
                    </a>
                @endforeach
            @else
                <p class="text-danger">No Results / Not Applicable</p>
            @endif
        <hr/>
        </div>

        <h5>All Departments</h5>
        <div class="mb-3">
            @if ($departments->count() > 0)
                @foreach ($departments as $dept)
                    <a class="btn btn-lg btn-outline-tertiary mx-1 mb-2"
                        href="{{ route('department.saveInSession', $dept['code']) }}" spoof spoof-method="POST">
                        {{ $dept['name'] }}
                    </a>
                @endforeach
            @else
                <p class="text-danger">No Results</p>
            @endif
        </div>
    </div>
</div>

@endsection
