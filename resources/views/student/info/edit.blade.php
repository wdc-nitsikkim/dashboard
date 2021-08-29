{{--
    $info -> (student model)->info relation
    $student -> single student model (with nested relations)
    $selectMenu -> 2-D array
    $semesters -> collection of semester model
    $canEdit -> boolean
--}}

@extends('layouts.admin', ['title' => 'Update Student Information - ' . $student->name])

@section('content')

@php
    $requiredField = '<span class="text-danger fw-bolder">*</span>';
    $isDisabled = $canEdit ? '' : 'disabled';
@endphp

@component('student.partials.pageHeading', [
        'student' => $student,
        'sideBtns' => [
            'backRedirect' => route('student.home', $student->roll_number)
        ]
    ])

    @if ($canEdit)
        <p>
            -> Mandatory fields are marked by {!! $requiredField !!}
            <br>
            -> Only you & authorized users <span class="fw-bolder text-info">(Admin, TnP, Office)</span> can view this information.
            <br>
            -> All links to your files <span class="fst-italic">(image, signature, resume, ..)</span> will expire in
            <span class="fw-bolder text-info">{{ CustomHelper::PRIVATE_URL_EXPIRE / 60 }} minutes.</span>
            Refresh the page <span class="fw-bolder">(save any unsaved information first)</span> to reset the
            expiration timeout.
        </p>
    @else
        <h4><code>Read-only mode</code></h4>
    @endif
@endcomponent

<form class="form-floating" action="{{ route('student.info.update', $student->roll_number) }}"
    method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row mb-3">
        <div class="col-12 col-lg-8">
            <div class="card card-body border-0 shadow mb-4">
                <h5 class="mb-3 me-4">Basic information</h5>

                <div class="row g-2 mb-2">
                    <div class="col-12 d-none d-md-block col-md-2 mb-2">
                        <div class="form-floating">
                            <input type="text" id="id" class="form-control" placeholder="ID"
                                value="{{ $student->id }}" disabled>
                            <label for="id">ID</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-7 col-md-6 mb-2">
                        <div class="form-floating">
                            <input type="text" id="name" class="form-control" placeholder="Name"
                                value="{{ $student->name }}" disabled>
                            <label for="name">Name</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-5 col-md-4 mb-2">
                        <div class="form-floating">
                            <input type="date"
                                class="form-control {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}"
                                id="date_of_birth" placeholder="Date of Birth" name="date_of_birth"
                                value="{{ old('date_of_birth') ?? $info->date_of_birth }}" required {{ $isDisabled }}>
                            <label for="date_of_birth">Date of Birth {!! $requiredField !!}</label>

                            @if ($errors->has('date_of_birth'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('date_of_birth') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-12 col-sm-6 mb-2">
                        <div class="form-floating">
                            <input type="email"
                                class="form-control {{ $errors->has('personal_email') ? 'is-invalid' : '' }}"
                                id="personal_email" placeholder="Personal email" name="personal_email"
                                value="{{ old('personal_email') ?? $info->personal_email }}" {{ $isDisabled }}>
                            <label for="personal_email">Personal email</label>

                            @if ($errors->has('personal_email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('personal_email') }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-12 col-sm-6 mb-2">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control {{ $errors->has('secondary_mobile') ? 'is-invalid' : '' }}"
                                id="secondary_mobile" placeholder="Secondary mobile" name="secondary_mobile"
                                value="{{ old('secondary_mobile') ?? $info->secondary_mobile }}" {{ $isDisabled }}>
                            <label for="secondary_mobile">Secondary mobile</label>

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
                                id="gender" name="gender" required {{ $isDisabled }}>
                                <option value="" selected disabled>Select</option>

                                @foreach ($selectMenu['genders'] as $gender)
                                    <option value="{{ $gender }}"
                                        {{ (old('gender') ?? $info->gender) == $gender ? 'selected' : '' }}>
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
                                id="blood_group" name="blood_group" required {{ $isDisabled }}>
                                <option value="" selected disabled>Select</option>

                                @foreach ($selectMenu['blood_groups'] as $blood_group)
                                    <option value="{{ $blood_group }}"
                                        {{ (old('blood_group') ?? $info->blood_group) == $blood_group ? 'selected' : '' }}>
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
                                id="category" name="category" required {{ $isDisabled }}>
                                <option value="" selected disabled>Select</option>

                                @foreach ($selectMenu['categories'] as $category)
                                    <option value="{{ $category }}"
                                        {{ (old('category') ?? $info->category) == $category ? 'selected' : '' }}>
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
                                id="religion" name="religion" required {{ $isDisabled }}>
                                <option value="" selected disabled>Select</option>

                                @foreach ($selectMenu['religions'] as $religion)
                                    <option value="{{ $religion }}"
                                        {{ (old('religion') ?? $info->religion) == $religion ? 'selected' : '' }}>
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

                @component('components.form.timestamps', [
                    'createdAt' => $info->created_at,
                    'updatedAt' => $info->updated_at
                ])
                @endcomponent

                @if ($canEdit)
                    @include('components.form.footerEdit', [
                        'returnRoute' => route('student.home', $student->roll_number),
                        'submitBtnTxt' => 'Save Information'
                    ])

                    <div class="row mb-3">
                        @if ($info->deleted_at == null)
                            <div class="col-12 d-grid mx-auto mt-n2 mb-2">
                                <a class="btn btn-danger" confirm alert-title="Mark information as deleted?"
                                    alert-text="Your information won't show up in search results after this, but will
                                    still be present." href="{{ route('student.info.softDelete', $student->roll_number) }}"
                                    spoof spoof-method="DELETE">
                                    <span class="material-icons ms-1">delete</span>
                                    Delete
                                </a>
                            </div>
                        @else
                            <div class="col-4 d-grid mx-auto mt-n2 mb-2">
                                <a class="btn btn-info" confirm alert-title="Restore Information?"
                                    href="{{ route('student.info.restore', $student->roll_number) }}"
                                    spoof spoof-method="POST"
                                    alert-text="Your information will become visible after this.">
                                    <span class="material-icons me-1">restore</span>
                                    Restore
                                </a>
                            </div>

                            @can('delete', ['App\\Models\StudentInfo', $student, $info])
                                <div class="col-8 d-grid mx-auto mt-n2 mb-2">
                                    <a class="btn btn-danger"
                                        href="{{ route('student.info.delete', $student->roll_number) }}"
                                        spoof spoof-method="DELETE"
                                        confirm alert-title="Delete Permanently?"
                                        alert-text="This cannot be undone!">
                                        <span class="material-icons me-1">delete_forever</span>
                                        Delete Permanently
                                    </a>
                                </div>
                            @else
                                <div class="col-8 d-grid mx-auto mt-n2 mb-2" data-bs-toggle="tooltip"
                                    title="You are not authorized to perform this action!">
                                    <button class="btn btn-danger" disabled>
                                        <span class="material-icons me-1">delete_forever</span>
                                        Delete Permanently
                                    </button>
                                </div>
                            @endcan

                        @endif
                    </div>

                @endif

            </div>
        </div>

        <div class="col-12 col-lg-4 col-md-12">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card card-body border-0 shadow">

                        <div class="col-12">

                            <div class="d-flex mx-1 align-items-start justify-content-between">
                                <h5 class="h5 mb-3 me-2">Picture</h5>
                                <div class="me-sm-0 me-3">
                                    <div class="form-check form-switch" data-bs-toggle="tooltip"
                                        title="This will remove the picture even if you have
                                        selected a new one" data-bs-placement="left">
                                        <input class="form-check-input" type="checkbox"
                                            id="remove_image" name="remove_image" {{ $isDisabled }}>
                                        <label class="small form-check-label" for="remove_image">
                                            Remove
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 d-flex align-items-center">
                                <div class="col-4 text-center mb-3 mb-xl-0 d-flex
                                    align-items-center justify-content-center">
                                    <div class="icon-shape rounded me-4 me-sm-0">

                                        @component('components.image', [
                                            'image' => $info->image ? route('privateStorage.url', $info->image) : null,
                                            'imgAttr' => 'id="image_preview"',
                                            'originalSrc' => true,
                                            'default' => true,
                                            'defaultSrc' => asset('static/images/user-default.png')
                                        ])
                                        @endcomponent

                                    </div>
                                </div>

                                <div class="col-8 file-field">
                                    <div class="d-flex justify-content-xl-center">
                                        <div class="d-flex">
                                            <span class="material-icons align-items-center me-2">
                                                add_a_photo</span>
                                            <input type="file" name="image" id="profile_image"
                                                class="{{ $errors->has('image') ? 'is-invalid' : '' }}"
                                                accept=".jpg, .jpeg, .png" preview="#image_preview" square previewable
                                                {{ $isDisabled }}>
                                            <div class="d-md-block text-left">
                                                <div class="fw-normal text-dark mb-1">Choose Image</div>
                                                <div class="text-gray-500 small">JPG, PNG, GIF. Max size of 800 kB</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($errors->has('image'))
                                <div class="my-2 text-danger">
                                    {{ $errors->first('image') }}
                                </div>
                            @endif
                        </div>

                        <hr />

                        <div class="col-12">
                            <div class="d-flex mx-1 align-items-start justify-content-between">
                                <h5 class="h5 mb-3 me-2">Signature</h5>
                                <div class="me-sm-0 me-3">
                                    <div class="form-check form-switch" data-bs-toggle="tooltip"
                                        title="This will remove the signature even if you have
                                        selected a new one" data-bs-placement="left">
                                        <input class="form-check-input" type="checkbox"
                                            id="remove_signature" name="remove_signature" {{ $isDisabled }}>
                                        <label class="small form-check-label" for="remove_signature">
                                            Remove
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 d-flex align-items-center">
                                <div class="col-4 text-center mb-3 mb-xl-0 d-flex
                                    align-items-center justify-content-center">
                                    <div class="icon-shape rounded me-4 me-sm-0">

                                        @component('components.image', [
                                            'image' => $info->signature ? route('privateStorage.url', $info->signature) : null,
                                            'imgAttr' => 'id="signature_preview"',
                                            'classes' => 'scale-on-hover',
                                            'originalSrc' => true,
                                            'default' => true,
                                            'defaultSrc' => asset('static/images/signature.svg')
                                        ])
                                            @isset($info->signature)
                                                @slot('urlWrapper')
                                                    {{ route('privateStorage.url', $info->signature) }}
                                                @endslot

                                                @slot('urlTooltip')
                                                    Click to view
                                                @endslot
                                            @endisset
                                        @endcomponent

                                    </div>
                                </div>

                                <div class="col-8 file-field">
                                    <div class="d-flex justify-content-xl-center">
                                        <div class="d-flex">
                                            <span class="material-icons align-items-center me-2">
                                                add_a_photo</span>
                                            <input type="file" name="signature"
                                                class="{{ $errors->has('signature') ? 'is-invalid' : '' }}"
                                                accept=".jpg, .jpeg, .png" preview="#signature_preview" previewable
                                                {{ $isDisabled }}>
                                            <div class="d-md-block text-left">
                                                <div class="fw-normal text-dark mb-1">Choose Signature</div>
                                                <div class="text-gray-500 small">JPG, PNG, GIF. Max size of 500 kB</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($errors->has('signature'))
                                <div class="my-2 text-danger">
                                    {{ $errors->first('signature') }}
                                </div>
                            @endif
                        </div>

                        <hr />

                        <div class="col-12">
                            <div class="d-flex mx-1 align-items-start justify-content-between">
                                <h5 class="h5 mb-3 me-2">Resume</h5>
                                <div class="me-sm-0 me-3">
                                    <div class="form-check form-switch" data-bs-toggle="tooltip"
                                        title="This will remove the resume even if you have
                                        selected a new one" data-bs-placement="left">
                                        <input class="form-check-input" type="checkbox"
                                            id="remove_resume" name="remove_resume" {{ $isDisabled }}>
                                        <label class="small form-check-label" for="remove_resume">
                                            Remove
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 d-flex align-items-center">
                                <div class="col-4 text-center mb-3 mb-xl-0 d-flex
                                    align-items-center justify-content-center">
                                    <div class="icon-shape rounded me-4 me-sm-0">

                                        @component('components.image', [
                                            'defaultIcon' => $info->resume == null ? 'picture_as_pdf' : 'cloud_done',
                                            'iconClasses' => $info->resume == null
                                                ? 'text-danger scale-on-hover' : 'text-success scale-on-hover'
                                        ])
                                            @isset($info->resume)
                                                @slot('urlWrapper')
                                                    {{ route('privateStorage.url', $info->resume) }}
                                                @endslot

                                                @slot('urlTooltip')
                                                    Click to view
                                                @endslot
                                            @endisset
                                        @endcomponent

                                    </div>
                                </div>

                                <div class="col-8 file-field">
                                    <div class="d-flex justify-content-xl-center">
                                        <div class="d-flex">
                                            <span class="material-icons align-items-center me-2">
                                                note_add</span>
                                            <input type="file" name="resume"
                                                class="{{ $errors->has('resume') ? 'is-invalid' : '' }}"
                                                accept=".pdf, .doc, .docx" {{ $isDisabled }}>
                                            <div class="d-md-block text-left">
                                                <div class="fw-normal text-dark mb-1">Choose Resume</div>
                                                <div class="text-gray-500 small">PDF, DOC, DOCX. Max size of 5 MB</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($info->resume == null)
                                <div class="mt-1 text-info">
                                    You haven't added your resume yet!
                                </div>
                            @else
                                <div class="mt-1 text-success">
                                    You have added a resume.
                                </div>
                            @endif

                            @if ($errors->has('resume'))
                                <div class="my-2 text-danger">
                                    {{ $errors->first('resume') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-body border-0 shadow mb-4">
                <h5 class="mb-4">Family Information</h5>

                <div class="row g-2 mb-2">
                    <div class="col-12 col-sm-6 col-lg-3 mb-2">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control {{ $errors->has('fathers_name') ? 'is-invalid' : '' }}"
                                id="fathers_name" placeholder="Father's name" name="fathers_name"
                                value="{{ old('fathers_name') ?? $info->fathers_name }}" required {{ $isDisabled }}>
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
                                value="{{ old('fathers_mobile') ?? $info->fathers_mobile }}" {{ $isDisabled }}>
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
                                value="{{ old('mothers_name') ?? $info->mothers_name }}" required {{ $isDisabled }}>
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
                                value="{{ old('mothers_mobile') ?? $info->mothers_mobile }}" {{ $isDisabled }}>
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
                            rows="3" {{ $isDisabled }}>{{ old('current_address') ?? $info->current_address }}</textarea>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <textarea class="form-control" name="permanent_address"
                            placeholder="Permanent adddress"
                            rows="3" {{ $isDisabled }}>{{ old('permanent_address') ?? $info->permanent_address }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card card-body border-0 shadow mb-4">
                <h5 class="mb-2">Educational Information</h5>

                <h6 class="fw-bolder">Class 10<sup>th</sup></h6>
                <div class="row g-2 mb-2">
                    @include('student.partials.editFormSchoolDetails', ['class' => '10th'])
                </div>

                <h6 class="fw-bolder">Class 12<sup>th</sup></h6>
                <div class="row g-2 mb-2">
                    @include('student.partials.editFormSchoolDetails', ['class' => '12th'])
                </div>

                <h6 class="fw-bolder">College</h6>
                <div class="row g-2 mb-2">
                    <div class="col-6 col-sm-4 mb-2">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control {{ $errors->has('cgpa') ? 'is-invalid' : '' }}"
                                id="cgpa" placeholder="CGPA" name="cgpa"
                                value="{{ old('cgpa') ?? $info->cgpa }}" min="0" max="10" step="0.01"
                                {{ $isDisabled }}>
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
                                id="till_sem" name="till_sem" {{ $isDisabled }}>
                                <option value="" selected>Select</option>

                                @foreach ($semesters as $sem)
                                    <option value="{{ $sem->id }}"
                                        {{ (old('till_sem') ?? $info->till_sem) == $sem->id ? 'selected' : '' }}>
                                        {{ $sem->name }}
                                    </option>
                                @endforeach

                            </select>
                            <label for="till_sem">Till Semester</label>
                            <small class="small helper-text ms-1">Required only if CGPA is added</small>

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

    @if ($canEdit)
        @include('components.form.footerEdit', [
            'returnRoute' => route('student.home', $student->roll_number),
            'submitBtnTxt' => 'Save Information'
        ])
    @endif


</form>

@endsection

@push('scripts')
    <script src="{{ asset('static/js/image-selector.js') }}"></script>
@endpush
