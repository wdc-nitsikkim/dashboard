@extends('layouts.admin')

@section('content')

@component('components.page.heading')
    @slot('heading')
        Add Student - {{ $batch['full_name'] }}
    @endslot

    @slot('subheading')
        @include('department.partials.studentsPageSubheading', ['batch' => $batch])

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
@endphp

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('department.students.saveNew', $baseRouteParams) }}" method="POST">
            {{ csrf_field() }}

            <div class="row g-2 mb-3">
                <div class="col-sm-12 mb-2">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            id="name" placeholder="Name of Student"
                            name="name" value="{{ old('name') }}" required>
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
                            value="{{ old('roll_number') }}" required>
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
                            id="email" placeholder="Email Address" name="email" value="{{ old('email') }}"
                            required>
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
                        <input type="text" class="form-control"
                            id="department" value="{{ $department['name'] }}" disabled>
                        <label for="department">Department</label>
                    </div>
                </div>
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control"
                            id="batch" value="{{ $batch['full_name'] }}" disabled>
                        <label for="batch">Batch</label>
                    </div>
                </div>
            </div>

            @component('components.form.footerAdd')
                @slot('returnRoute')
                    {{ route('department.students.show', $baseRouteParams) }}
                @endslot

                @slot('submitBtnTxt')
                    Add Student
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection

@push('scripts')
    {{-- <script src="{{ asset('static/js/students.js') }}"></script> --}}
@endpush
