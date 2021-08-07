@extends('layouts.admin', ['title' => 'Edit Profile'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Edit Profile
    @endslot

    @slot('subheading')
        Some fields can only be updated by an administrator
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

@php
    $userType = old('type') ?? $profile['type'];
    $userDept = old('department_id') ?? $profile['department_id'];
@endphp

<form method="POST" action="{{ route('admin.profiles.update', $profile['id']) }}"
    enctype="multipart/form-data">

    {{ csrf_field() }}

    <div class="row mb-4">
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
                                value="{{ old('name') ?? $profile['name'] }}"
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
                                value="{{ old('designation') ?? $profile['designation'] }}" required>
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
                                value="{{ old('email') ?? $profile['email'] }}"
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
                                value="{{ old('mobile') ?? $profile['mobile'] }}"
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

                            @can('updateDepartment', 'App\\Models\\Profile')
                                <select class="form-select {{ $errors->has('department_id') ? 'is-invalid' : '' }}"
                                    id="department_id" name="department_id" required>

                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept['id'] }}"
                                            {{ $userDept == $dept['id'] ? 'selected' : '' }}>
                                            {{ $dept['name'] }}
                                        </option>
                                    @endforeach

                                </select>
                            @else
                                <input type="hidden" name="department_id"
                                    value="{{ $profile['department_id'] }}" readonly>
                                <input type="text"
                                    class="form-control {{ $errors->has('department_id') ? 'is-invalid' : '' }}"
                                    id="department_id" placeholder="Department"
                                    value="{{ $profile['department']['name'] }}" readonly>
                                <small class="text-gray-500">-> Can be updated by authorized
                                    users only</small>
                            @endcan

                            <label for="department">Select Department</label>

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
                <div class="col-12 mb-4">
                    <div class="card card-body border-0 shadow">

                        <h5 class="mb-3">Change profile photo</h5>
                        <div class="row mb-2 d-flex align-items-center">
                            <div class="col-4 text-center mb-3 mb-xl-0 d-flex
                                align-items-center justify-content-center">
                                <div class="icon-shape rounded me-4 me-sm-0">

                                    @if (isset($profile['image']) && Storage::disk('public')->exists($profile['image']))
                                        <img class="rounded-circle" alt="profile-image"
                                            src="{{ asset(Storage::url($profile['image'])) }}"
                                            original-src="{{ asset(Storage::url($profile['image'])) }}"
                                            id="image_preview" />
                                    @else
                                        <img class="rounded-circle" alt="default-image"
                                            src="{{ asset('static/images/user-default.png') }}"
                                            original-src="{{ asset('static/images/user-default.png') }}"
                                            id="image_preview" />
                                    @endif

                                </div>
                            </div>

                            <div class="col-8 file-field">
                                <div class="d-flex justify-content-xl-center">
                                    <div class="d-flex">
                                        <span class="material-icons align-items-center me-2">
                                            add_a_photo</span>
                                        <input type="file" name="profile_image" id="profile_image"
                                            class="{{ $errors->has('profile_image') ? 'is-invalid' : '' }}"
                                            accept=".jpg, .jpeg, .png">
                                        <div class="d-md-block text-left">
                                            <div class="fw-normal text-dark mb-1">Choose Image</div>
                                            <div class="text-gray-500 small">JPG or PNG. Max size of 800 kB</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($errors->has('profile_image'))
                            <div class="my-2 text-danger">
                                {{ $errors->first('profile_image') }}
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-12">
                    <div class="card card-body border-0 shadow">
                        <h2 class="h5 mb-3">Profile Association</h2>

                        @if (is_null($profile['userLink']))
                            <p class="small fw-bold text-danger">-> This profile is not associated with
                                any user account</p>
                        @elseif (Auth::id() == $profile['userLink']['user_id'])
                            <p class="small fw-bold text-success">-> This profile is linked to
                                your account</p>
                        @else
                            <p class="small text-info">-> This profile is linked to
                                another account</p>
                        @endif

                        <p class="small text-info">-> Only authorized users can manage this setting</p>

                        <div class="row mb-3">
                            <div class="col-sm-12 d-grid gap-1 mx-auto mb-3">
                                <a class="btn btn-primary" href="{{ route('admin.profiles.show') }}">
                                    Cancel
                                    <span class="material-icons ms-1">cancel</span>
                                </a>
                            </div>
                            <div class="col-sm-12 d-grid gap-1 mx-auto mb-3">
                                <button class="btn btn-success" type="submit">
                                    Save Changes
                                    <span class="material-icons ms-1">update</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-body border-0 shadow mb-4">

                <h5 class="mb-4">Additional Information</h5>

                <div class="row g-2 mb-2">
                    <div class="col-12 mb-2">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control {{ $errors->has('work_experience') ? 'is-invalid' : '' }}"
                                id="work_experience" placeholder="Experience" name="work_experience"
                                value="{{ old('work_experience') ?? $profile['work_experience'] }}">
                            <label for="work_experience">Experience</label>
                            <small class="small text-gray-500">Eg. 4 years</small>

                            @if ($errors->has('work_experience'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('work_experience') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6 mb-2">
                        <div class="mb-2">
                            <label for="textarea" class="form-label">
                                Academic Qualifications</label>
                            <textarea class="form-control" name="academic_qualifications"
                                placeholder="Enter your academic qualifications here"
                                rows="3">{{ old('academic_qualifications') ?? $profile['academic_qualifications'] }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="mb-2">
                            <label for="textarea" class="form-label">
                                Office Address</label>
                            <textarea class="form-control" name="office_address"
                                placeholder="Your office address"
                                rows="3">{{ old('office_address') ?? $profile['office_address'] }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6 mb-2">
                        <div class="mb-2">
                            <label for="textarea" class="form-label">
                                Areas of Interest</label>
                            <textarea class="form-control" name="areas_of_interest"
                                placeholder="Add your areas of interest"
                                rows="3">{{ old('areas_of_interest') ?? $profile['areas_of_interest'] }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="mb-2">
                            <label for="textarea" class="form-label">
                                Teachings</label>
                            <textarea class="form-control" name="teachings"
                                placeholder="Subjects/Topics you teach"
                                rows="3" >{{ old('teachings') ?? $profile['teachings'] }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card card-body border-0 shadow mb-4">

                <div class="d-flex mx-1 align-items-start justify-content-between">
                    <h2 class="h5 mb-4 me-3">Publications & Extras</h2>
                    <div class="me-sm-0 me-3">
                        <span class="me-2 small" id="editor_status">Initializing Editor...</span>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-gray"
                                data-bs-toggle="tooltip" title="Restore server data"
                                data-bs-placement="left" id="editor_server_restore">
                                <span class="material-icons text-success">
                                    cloud_done</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-gray"
                                data-bs-toggle="tooltip" title="Restore from local storage"
                                data-bs-placement="left" id="editor_local_restore">
                                <span class="material-icons text-info">
                                    settings_backup_restore</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-gray"
                                data-bs-toggle="tooltip" title="Reset Editor"
                                data-bs-placement="left" id="editor_failsafe">
                                <span class="material-icons text-danger">
                                    close</span>
                            </button>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="publications" name="publications"
                    value='{{ old('publications') ?? $profile['publications'] }}'
                    original='{{ $profile['publications'] }}'>

                <div class="row mx-1 g-2 mb-2">
                    <div class="col-sm-12 border border-2 rounded mb-3">
                        <div class="p-2" id="publications-editor">

                        </div>
                    </div>
                </div>

                @include('components.form.footerEdit', [
                    'returnRoute' => route('admin.profiles.show'),
                    'submitBtnTxt' => 'Update Profile'
                ])

            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
    @include('includes.editorjs-scripts')

    <script src="{{ asset('static/js/profile.js') }}"></script>
    <script>
        let check = {!! $profile['publications'] !!};
    </script>
@endpush
