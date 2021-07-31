@extends('layouts.admin')

@section('content')

<div class="my-3">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Edit Student Details</h1>
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

    $routeParamsWithId = array_merge(
        $baseRouteParams,
        ['student' => $student]
    );
@endphp

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('department.students.update', $routeParamsWithId) }}" method="POST">
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
                        <select class="form-select {{ $errors->has('department') ? 'is-invalid' : '' }}"
                            id="department" name="department" required>

                            @foreach ($departmentList as $dept)
                                <option value="{{ $dept['id'] }}"
                                    {{ $dept['id'] == $student['department_id'] ? 'selected' : '' }}>
                                    {{ $dept['name'] }}
                                </option>
                            @endforeach

                        </select>
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
                            id="batch" value="{{ $batch['full_name'] }}" disabled>
                        <label for="batch">Batch</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 d-grid gap-1 mx-auto mb-3">
                    <a class="btn btn-primary"
                        href="{{ route('department.students.show', $baseRouteParams) }}">
                        Cancel <span class="material-icons ms-1">cancel</span>
                    </a>
                </div>
                <div class="col-sm-3 d-grid mx-auto mb-3">
                    <button class="btn btn-info" type="reset">
                        <span class="material-icons me-1">undo</span>
                        Reset
                    </button>
                </div>
                <div class="col-sm-6 d-grid gap-1 mx-auto mb-3">
                    <button class="btn btn-success" type="submit">
                        Update <span class="material-icons ms-1">update</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    {{-- <script src="{{ asset('static/js/students.js') }}"></script> --}}
@endpush
