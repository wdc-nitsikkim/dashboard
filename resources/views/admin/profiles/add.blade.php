{{--
    $canChooseType -> boolean
    $canCustomizeLink -> boolean
    $departments -> collection of department model
    $userType -> string
--}}

@extends('layouts.admin', ['title' => 'Create Profile'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Create a new profile
    @endslot

    @slot('subheading')
        This is different from the user account you created while registering
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

@php
    $userType = old('type') ?? $userType;
@endphp

<form class="" method="POST" action="{{ route('admin.profiles.saveNew') }}">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-12 col-lg-8 col-md-12">
            <div class="card card-body border-0 shadow mb-4">
                <div class="d-flex align-items-start justify-content-between">
                    <h2 class="h5 mb-4 me-4">Basic information</h2>
                    <div class="float-end">
                        <div class="form-check form-switch" data-bs-toggle="tooltip"
                            title="Copy data from user account"
                            data-bs-placement="left">
                            <input class="form-check-input" type="checkbox"
                                id="copy_user_data">
                            <label class="small form-check-label" for="copy_user_data">
                                Copy
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6 mb-2">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                id="name" placeholder="Name" name="name"
                                value="{{ old('name') }}"
                                data-account-value="{{ Auth::user()->name }}" required>
                            <label for="name">Name</label>

                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control {{ $errors->has('designation') ? 'is-invalid' : '' }}"
                                id="designation" placeholder="Designation" name="designation"
                                value="{{ old('designation') }}" required>
                            <label for="designation">Designation</label>

                            @if ($errors->has('designation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('designation') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-12 mb-2">
                        <div class="form-floating">
                            <input type="email"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                id="email" placeholder="E-mail" name="email"
                                value="{{ old('email') }}"
                                data-account-value="{{ Auth::user()->email }}" required>
                            <label for="email">E-mail</label>
                            <small class="small">Use '@nitsikkim.ac.in' email addresses</small>

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-8 mb-2">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}"
                                id="mobile" placeholder="Mobile Number" name="mobile"
                                value="{{ old('mobile') }}"
                                data-account-value="{{ Auth::user()->mobile }}" required>
                            <label for="mobile">Mobile Number</label>

                            @if ($errors->has('mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mobile') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>

            <div class="card card-body border-0 shadow mb-4 mb-xl-0">
                <h2 class="h5 mb-4">Institute Related Information</h2>

                <div class="row">
                    <div class="col-sm-6 mb-2">
                        <h6 class="mb-3">Select which applies</h6>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="type_1"
                                value="faculty" required
                                {{ $canChooseType ? '' : 'readonly' }}
                                {{ $userType == 'faculty' ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_1">
                                Faculty
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="type_2"
                                value="staff" required
                                {{ $canChooseType ? '' : 'readonly' }}
                                {{ $userType == 'staff' ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_2">
                                Staff
                            </label>
                        </div>
                        {{-- <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="type_3" value="other">
                            <label class="form-check-label" for="type_3">
                                Other
                            </label>
                        </div> --}}
                    </div>

                    <div class="col-sm-6 mb-2">
                        <div class="form-floating">
                            <select class="form-select {{ $errors->has('department_id') ? 'is-invalid' : '' }}"
                                id="department_id" name="department_id" required>
                                <option value="" selected disabled>Select a department</option>

                                @foreach ($departments as $dept)
                                    <option value="{{ $dept['id'] }}"
                                        {{ old('department_id') == $dept['id'] ? 'selected' : '' }}>
                                        {{ $dept['name'] }}
                                    </option>
                                @endforeach

                            </select>
                            <label for="department">Select Department</label>
                            <small class="text-info fw-bold">-> Cannot be changed later!</small>

                            @if ($errors->has('department_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('department_id') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body border-0 shadow">
                        <h2 class="h5 mb-4">Link this profile to your account?</h2>

                        <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                            <div>
                                <h3 class="h6 mb-1">Confirm Action</h3>
                                <p class="small pe-4">
                                    A user can link only a single profile to his/her account.
                                    <strong>Continue?</strong>
                                </p>
                            </div>
                            <div>
                                <div class="form-check form-switch">

                                    @if (Auth::user()->hasProfile())
                                        <input class="form-check-input" type="checkbox"
                                            name="link_account" id="link_account" disabled>
                                    @else
                                        <input class="form-check-input" type="checkbox"
                                            name="link_account" id="link_account"
                                            {{ $canCustomizeLink ? '' : 'checked readonly' }}>
                                    @endif

                                </div>
                            </div>
                        </li>

                        @if (Auth::user()->hasProfile())
                            <p class="small fw-bold text-danger">This account is already linked to
                                a profile. Cannot link to another!
                            </p>
                        @elseif (Auth::user()->hasRole('hod', 'faculty', 'staff'))
                            <p class="small fw-bold text-success">This profile will be linked to your
                                account. You can edit it later</p>
                        @endif

                        <p class="small text-info">Only authorized users can modify this setting
                            once linked</p>

                        <div class="row mb-3">
                            <div class="col-sm-12 d-grid gap-1 mx-auto mb-3">
                                <a class="btn btn-primary" href="{{ route('admin.profiles.show') }}">
                                    Cancel
                                    <span class="material-icons ms-1">cancel</span>
                                </a>
                            </div>
                            <div class="col-sm-12 d-grid gap-1 mx-auto mb-3">
                                <button class="btn btn-success" type="submit">
                                    Create Profile
                                    <span class="material-icons ms-1">keyboard_arrow_right</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
    <script src="{{ asset('static/js/profile.js') }}"></script>
@endpush
