@extends('layouts.admin')

@section('content')

@php
    $departmentModel = 'App\\Models\\Department';
@endphp

@can (['create', 'update'], $departmentModel)
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mt-3">
        <div>
            <div class="dropdown">
                <button class="btn btn-secondary d-inline-flex align-items-center me-2 dropdown-toggle"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="material-icons mx-1">add</span>
                    New
                </button>
                <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">

                    @can ('create', $departmentModel)
                        <a class="dropdown-item d-flex align-items-center"
                            href="">
                            <span class="material-icons">add_circle</span>
                            Create New Department
                        </a>
                    @endcan

                    @can ('update', $departmentModel)
                        <a class="dropdown-item d-flex align-items-center"
                            href="">
                            <span class="material-icons">edit</span>
                            Edit Existing
                        </a>
                    @endcan

                </div>
            </div>
        </div>
    </div>
@endcan

@component('components.pageHeading')
    @slot('heading')
        Select a department to proceed
    @endslot

    @slot('sideButtons')
        <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
            <span class="material-icons mx-1">help</span>
        </a>
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <h5>Additional Access</h5>
        <div class="mb-3">

            @if ($preferred->count() > 0)
                @foreach ($preferred as $dept)
                    @component('components.anchorBtn', [
                            'href' => route('department.saveInSession', $dept['code']),
                            'classes' => 'btn-lg btn-outline-tertiary mb-2'
                        ])
                        @slot('attr')
                            spoof spoof-method="POST"
                        @endslot
                        {{ $dept['name'] }}
                    @endcomponent
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
                    @component('components.anchorBtn', [
                            'href' => route('department.saveInSession', $dept['code']),
                            'classes' => 'btn-lg btn-outline-tertiary mb-2'
                        ])
                        @slot('attr')
                            spoof spoof-method="POST"
                        @endslot
                        {{ $dept['name'] }}
                    @endcomponent
                @endforeach
            @else
                <p class="text-danger">No Results</p>
            @endif

        </div>
    </div>
</div>

@endsection
