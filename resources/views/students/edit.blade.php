@extends('layouts.admin', ['title' => 'Edit Student - ' . $department['name']])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Update Student - {{ $batch['name'] }}
    @endslot

    @slot('subheading')
        @include('students.partials.subheading', ['batch' => $batch])

        {{ $department['name'] }}
    @endslot

    @slot('sideButtons')
        <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
            <span class="material-icons mx-1">help</span>
        </a>
    @endslot
@endcomponent

@php
    $baseRouteParams = [
        'dept' => $department,
        'batch' => $batch
    ];

    $routeParamsWithId = array_merge(
        $baseRouteParams,
        ['student' => $student]
    );
@endphp

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('students.update', $routeParamsWithId) }}" method="POST">
            {{ csrf_field() }}

            <div class="row g-2 mb-3">
                <div class="col-sm-2 col-md-2 mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="id"
                            placeholder="Name of Student" value="{{ $student['id'] }}" disabled>
                        <label for="name">Identifier</label>
                    </div>
                </div>
                <div class="col-sm-10 col-md-10 mb-2">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            id="name" placeholder="Name of Student"
                            name="name" value="{{ old('name') ?? $student['name'] }}" required>
                        <label for="name">Name of Student</label>

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('roll_number') ? 'is-invalid' : '' }}"
                            id="roll_number" placeholder="Roll Number" name="roll_number"
                            value="{{ old('roll_number') ?? $student['roll_number'] }}" required>
                        <label for="roll_number">Roll Number</label>
                        <small class="text-muted">B180071EC</small>

                        @if ($errors->has('roll_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('roll_number') }}
                            </div>
                        @endif

                    </div>
                </div>
                <div class="col-sm-8 mb-2">
                    <div class="form-floating">
                        <input type="email"
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            id="email" placeholder="Email Address" name="email"
                            value="{{ old('email') ?? $student['email'] }}" required>
                        <label for="email">Email Address</label>
                        <small class="text-muted">example@nitsikkim.ac.in</small>

                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-sm-8 mb-2">
                    <div class="form-floating">

                        @can('updateDepartment', 'App\\Models\\Student')
                            <select class="form-select {{ $errors->has('department') ? 'is-invalid' : '' }}"
                                id="department" name="department" required>

                                @foreach ($departmentList as $dept)
                                    <option value="{{ $dept['id'] }}"
                                        {{ $dept['id'] == $student['department_id'] ? 'selected' : '' }}>
                                        {{ $dept['name'] }}
                                    </option>
                                @endforeach

                            </select>
                        @else
                            <input type="text"
                                class="form-control {{ $errors->has('department') ? 'is-invalid' : '' }}"
                                id="department" placeholder="Department" name="department"
                                value="{{ old('department') ?? $department['name'] }}" readonly>
                        @endcan

                        <label for="department">Department</label>

                        @if ($errors->has('department'))
                            <div class="invalid-feedback">
                                {{ $errors->first('department') }}
                            </div>
                        @endif

                    </div>
                </div>
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control"
                            id="batch" value="{{ $batch['name'] }}" disabled>
                        <label for="batch">Batch</label>
                    </div>
                </div>
            </div>

            @component('components.form.timestamps', [
                    'createdAt' => $student['created_at'],
                    'updatedAt' => $student['updated_at']
                ])
            @endcomponent

            @component('components.form.footerEdit')
                @slot('returnRoute')
                    {{ route('students.show', $baseRouteParams) }}
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection

@push('scripts')
    {{-- <script src="{{ asset('static/js/students.js') }}"></script> --}}
@endpush
