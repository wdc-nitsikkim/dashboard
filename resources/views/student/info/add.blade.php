{{--
    $student -> single student model (with nested relations)
    $selectMenu -> 2-D array
    $semesters -> collection of semester model
--}}

@extends('layouts.admin', ['title' => 'Add Student Information - ' . $student->name])

@section('content')

@php
    $requiredField = '<span class="text-danger fw-bolder">*</span>';
@endphp

@component('student.partials.pageHeading', [
        'student' => $student
    ])
    <p class="text-info">Mandatory fields are marked by {!! $requiredField !!}</p>
@endcomponent

<form class="form-floating" action="{{ route('student.saveNew', $student->roll_number) }}"
    target="_blank" method="POST">
    {{ csrf_field() }}

    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-body border-0 shadow mb-4">
                <h5 class="mb-3 me-4">Basic information</h5>

                <div class="row g-2 mb-2">
                    <div class="col-12 col-sm-7 col-lg-3 mb-2">
                        <div class="form-floating">
                            <input type="text" id="name" class="form-control" placeholder="Name"
                                value="{{ $student->name }}" disabled>
                            <label for="name">Name</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-5 col-lg-3 mb-2">
                        <div class="form-floating">
                            <input type="date"
                                class="form-control {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}"
                                id="date_of_birth" placeholder="Date of Birth" name="date_of_birth"
                                value="{{ old('date_of_birth') }}" required>
                            <label for="date_of_birth">Date of Birth {!! $requiredField !!}</label>

                            @if ($errors->has('date_of_birth'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('date_of_birth') }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 mb-2">
                        <div class="form-floating">
                            <input type="email"
                                class="form-control {{ $errors->has('personal_email') ? 'is-invalid' : '' }}"
                                id="personal_email" placeholder="Personal email address" name="personal_email"
                                value="{{ old('personal_email') }}">
                            <label for="personal_email">Personal email address</label>

                            @if ($errors->has('personal_email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('personal_email') }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 mb-2">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control {{ $errors->has('secondary_mobile') ? 'is-invalid' : '' }}"
                                id="secondary_mobile" placeholder="Secondary Mobile Number" name="secondary_mobile"
                                value="{{ old('secondary_mobile') }}">
                            <label for="secondary_mobile">Secondary mobile number</label>

                            @if ($errors->has('secondary_mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('secondary_mobile') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-6 col-sm-4 col-md-3 mb-2">
                        <div class="form-floating">
                            <select class="form-select {{ $errors->has('gender') ? 'is-invalid' : '' }}"
                                id="gender" name="gender" required>
                                <option value="" selected disabled>Select</option>

                                @foreach ($selectMenu['genders'] as $gender)
                                    <option value="{{ $gender }}"
                                        {{ old('gender') == $gender ? 'selected' : '' }}>
                                        {{ ucfirst($gender) }}
                                    </option>
                                @endforeach

                            </select>
                            <label for="department">Gender {!! $requiredField !!}</label>

                            @if ($errors->has('gender'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('gender') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-6 col-sm-4 col-md-3 mb-2">
                        <div class="form-floating">
                            <select class="form-select {{ $errors->has('blood_group') ? 'is-invalid' : '' }}"
                                id="blood_group" name="blood_group" required>
                                <option value="" selected disabled>Select</option>

                                @foreach ($selectMenu['blood_groups'] as $blood_group)
                                    <option value="{{ $blood_group }}"
                                        {{ old('blood_group') == $blood_group ? 'selected' : '' }}>
                                        {{ $blood_group }}
                                    </option>
                                @endforeach

                            </select>
                            <label for="department">Blood Group {!! $requiredField !!}</label>

                            @if ($errors->has('blood_group'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('blood_group') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-6 col-sm-4 col-md-3 mb-2">
                        <div class="form-floating">
                            <select class="form-select {{ $errors->has('category') ? 'is-invalid' : '' }}"
                                id="category" name="category" required>
                                <option value="" selected disabled>Select</option>

                                @foreach ($selectMenu['categories'] as $category)
                                    <option value="{{ $category }}"
                                        {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ strtoupper($category) }}
                                    </option>
                                @endforeach

                            </select>
                            <label for="department">Category {!! $requiredField !!}</label>

                            @if ($errors->has('category'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('category') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-6 col-sm-4 col-md-3 mb-2">
                        <div class="form-floating">
                            <select class="form-select {{ $errors->has('religion') ? 'is-invalid' : '' }}"
                                id="religion" name="religion" required>
                                <option value="" selected disabled>Select</option>

                                @foreach ($selectMenu['religions'] as $religion)
                                    <option value="{{ $religion }}"
                                        {{ old('religion') == $religion ? 'selected' : '' }}>
                                        {{ ucfirst($religion) }}
                                    </option>
                                @endforeach

                            </select>
                            <label for="department">Religion {!! $requiredField !!}</label>

                            @if ($errors->has('religion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('religion') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-body border-0 shadow mb-4">
                <h5 class="mb-4">Family Information</h5>

                <div class="row g-2 mb-2">
                    <div class="col-12 col-sm-6 col-lg-3 mb-2">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control {{ $errors->has('fathers_name') ? 'is-invalid' : '' }}"
                                id="fathers_name" placeholder="Father's name" name="fathers_name"
                                value="{{ old('fathers_name') }}" required>
                            <label for="fathers_name">Father's name {!! $requiredField !!}</label>

                            @if ($errors->has('fathers_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fathers_name') }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 mb-2">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control {{ $errors->has('fathers_mobile') ? 'is-invalid' : '' }}"
                                id="fathers_mobile" placeholder="Father's mobile number" name="fathers_mobile"
                                value="{{ old('fathers_mobile') }}">
                            <label for="fathers_mobile">Father's mobile number</label>

                            @if ($errors->has('fathers_mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fathers_mobile') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 mb-2">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control {{ $errors->has('mothers_name') ? 'is-invalid' : '' }}"
                                id="mothers_name" placeholder="Mother's name" name="mothers_name"
                                value="{{ old('mothers_name') }}" required>
                            <label for="mothers_name">Mother's name {!! $requiredField !!}</label>

                            @if ($errors->has('mothers_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mothers_name') }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 mb-2">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control {{ $errors->has('mothers_mobile') ? 'is-invalid' : '' }}"
                                id="mothers_mobile" placeholder="Mother's mobile number" name="mothers_mobile"
                                value="{{ old('mothers_mobile') }}">
                            <label for="mothers_mobile">Mother's mobile number</label>

                            @if ($errors->has('mothers_mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mothers_mobile') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-6 mb-2">
                        <textarea class="form-control" name="current_address"
                            placeholder="Current address"
                            rows="3">{{ old('current_address') }}</textarea>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <textarea class="form-control" name="permanent_address"
                            placeholder="Permanent adddress"
                            rows="3">{{ old('permanent_address') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card card-body border-0 shadow mb-4">
                <h5 class="mb-2">Educational Information</h5>

                <h6 class="fw-bolder">Class 10<sup>th</sup></h6>
                <div class="row g-2 mb-2">
                    @include('student.partials.addFormSchoolDetails', ['class' => '10th'])
                </div>

                <h6 class="fw-bolder">Class 12<sup>th</sup></h6>
                <div class="row g-2 mb-2">
                    @include('student.partials.addFormSchoolDetails', ['class' => '12th'])
                </div>

                <h6 class="fw-bolder">College</h6>
                <div class="row g-2 mb-2">
                    <div class="col-6 col-sm-4 mb-2">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control {{ $errors->has('cgpa') ? 'is-invalid' : '' }}"
                                id="cgpa" placeholder="CGPA" name="cgpa"
                                value="{{ old('cgpa') }}" min="0" max="10" step="0.01">
                            <label for="cgpa">CGPA</label>

                            @if ($errors->has('cgpa'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cgpa') }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-6 col-sm-4 mb-2">
                        <div class="form-floating">
                            <select class="form-select {{ $errors->has('till_sem') ? 'is-invalid' : '' }}"
                                id="till_sem" name="till_sem">
                                <option value="" selected disabled>Select</option>

                                @foreach ($semesters as $sem)
                                    <option value="{{ $sem->id }}"
                                        {{ old('till_sem') == $sem->id ? 'selected' : '' }}>
                                        {{ $sem->name }}
                                    </option>
                                @endforeach

                            </select>
                            <label for="till_sem">Till Semester</label>
                            <small class="small helper-text ms-1">Only fill if you are adding CGPA</small>

                            @if ($errors->has('till_sem'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('till_sem') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('components.form.footerAdd', [
        'returnRoute' => route('student.home', $student->roll_number),
        'submitBtnTxt' => 'Save Information'
    ])

</form>

@endsection
