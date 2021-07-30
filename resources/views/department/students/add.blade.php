@extends('layouts.admin')

@section('content')

<div class="my-3">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Add Student</h1>
            <p class="mb-0">
                @if ($batch['type'] == 'b')
                    B.Tech ({{ $batch['start_year'] . ' - ' . ($batch['start_year'] + 4)  }})
                @else
                    M.Tech ({{ $batch['start_year'] . ' - ' . ($batch['start_year'] + 2) }})
                @endif
                ,
                {{ $department['name'] }}
            </p>
        </div>
        <div>
            <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <span class="material-icons mx-1">help</span>
            </a>
        </div>
    </div>
</div>

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

            <div class="row mb-3">
                <div class="col-sm-4 d-grid gap-1 mx-auto mb-3">
                    <a class="btn btn-primary"
                        href="{{ route('department.students.show', $baseRouteParams) }}">
                        Cancel <span class="material-icons mx-1">cancel</span>
                    </a>
                </div>
                <div class="col-sm-8 d-grid gap-1 mx-auto mb-3">
                    <button class="btn btn-success" type="submit">
                        Add Student <span class="material-icons mx-1">keyboard_arrow_right</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/student.js') }}"></script>
@endpush
