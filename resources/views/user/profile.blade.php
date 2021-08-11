{{--
    $user -> single user model with all its relations (nested++)
    $canManage -> boolean
    $canUpdate -> boolean
    $canDelete -> boolean
--}}

@extends('layouts.admin', ['title' => 'Account Settings - ' . $user->name])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Account Settings
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

<form method="POST" action="{{ route('user.update', $user->id) }}"
    enctype="multipart/form-data">

    {{ csrf_field() }}

    <div class="row mb-4">
        <div class="col-12 col-lg-8 col-md-12">
            <div class="card card-body border-0 shadow mb-4">
                <div class="d-flex align-items-start justify-content-between">
                    <h2 class="h5 mb-4 me-4">Basic information</h2>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-12 mb-2">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                id="name" placeholder="Name" name="name"
                                value="{{ old('name') ?? $user['name'] }}" required>
                            <label for="name">Name</label>

                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-8 mb-2">
                        <div class="form-floating">
                            <input type="email"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                id="email" placeholder="E-mail" name="email"
                                value="{{ old('email') ?? $user['email'] }}" required>
                            <label for="email">E-mail</label>
                            <small class="small">Use '@nitsikkim.ac.in' email address
                                to enable login via Google</small>

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-4 mb-2">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}"
                                id="mobile" placeholder="Mobile Number" name="mobile"
                                value="{{ old('mobile') ?? $user['mobile'] }}" required>
                            <label for="mobile">Mobile Number</label>

                            @if ($errors->has('mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mobile') }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                @component('components.form.timestamps', [
                    'createdAt' => $user['created_at'],
                    'updatedAt' => $user['updated_at']
                ])
                @endcomponent

            </div>

            <div class="card card-body border-0 shadow mb-4 mb-xl-0">
                <h5 class="mb-4">Account roles & permissions</h5>

                @if ($user->roles->count() == 0)
                    <div class="alert alert-warning fade show my-2" role="alert">
                        No roles have been assigned to this account!
                    </div>
                @else
                    @php
                        $accordId = 'accord-useraccount-' . CustomHelper::getRandomStr(4);
                    @endphp

                    <div class="accordian mb-3" id='{{ $accordId }}'>

                        @foreach ($user->roles as $role)
                            @php
                                $rowId = $accordId . '-item-' . $loop->iteration;
                                $headerId = $accordId . '-header-' . $loop->iteration;
                            @endphp

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="{{ $headerId }}">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $rowId }}"
                                        aria-expanded="false" aria-controls="{{ $rowId }}">
                                        <span class="material-icons"></span>
                                        {{'-> ' . ucfirst($role->role) }}
                                    </button>
                                </h2>

                                <div id="{{ $rowId }}" class="accordion-collapse collapse"
                                    aria-labelledby={{ $headerId }}
                                    data-bs-parent="#{{ $accordId }}">
                                    <div class="accordion-body">

                                        @if ($role->permissions->count() == 0)
                                            <span class="text-danger">This role
                                                has no permission!
                                            </span>
                                        @else
                                            @foreach ($role->permissions as $permission)
                                                <span class="badge bg-purple text-dark me-1">
                                                    {{ ucfirst(CustomHelper::getInversePermissionMap()[$permission->permission]) }}
                                                </span>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                @endif

            </div>
        </div>

        <div class="col-12 col-lg-4 col-md-12">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card card-body border-0 shadow">

                        <div class="d-flex mx-1 align-items-start justify-content-between">
                            <h5 class="h5 mb-3 me-2">Account Picture</h5>
                            <div class="me-sm-0 me-3">
                                <div class="form-check form-switch" data-bs-toggle="tooltip"
                                    title="This will remove the picture even if you have
                                    selected a new one" data-bs-placement="left">
                                    <input class="form-check-input" type="checkbox"
                                        id="remove_profile_image" name="remove_profile_image">
                                    <label class="small form-check-label" for="remove_profile_image">
                                        Remove
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 d-flex align-items-center">
                            <div class="col-4 text-center mb-3 mb-xl-0 d-flex
                                align-items-center justify-content-center">
                                <div class="icon-shape rounded me-4 me-sm-0">

                                    @if (isset($user['image']) && Storage::disk('public')->exists($user['image']))
                                        <img class="rounded-circle" alt="profile-image"
                                            src="{{ asset(Storage::url($user['image'])) }}"
                                            original-src="{{ asset(Storage::url($user['image'])) }}"
                                            id="image_preview" />
                                    @elseif (isset($user['image']) && filter_var($user['image'], FILTER_VALIDATE_URL))
                                        <img class="rounded-circle" alt="url-image"
                                            src="{{ $user['image'] }}" original-src="{{ $user['image'] }}"
                                            id="image_preview"/>
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
                                            <div class="text-gray-500 small">JPG, PNG, GIF. Max size of 800 kB</div>
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
                        <h2 class="h5 mb-3">Account Association</h2>

                        @if (is_null($user->profileLink))
                            <p class="small fw-bold text-danger">-> This account is not associated with
                                any profile</p>
                        @else
                            <p class="small fw-bold text-success">-> This account is linked to
                                a profile <a target="_blank" class="ms-1"
                                    href="{{ route('admin.profiles.edit', $user->profileLink->profile_id) }}">
                                        <span class="material-icons">open_in_new</span></a>
                            </p>
                        @endif
                        <p class="small fw-bold text-danger">-> This account is not associated with any student</p>

                        <p class="small text-info">-> Only authorized users can manage this setting</p>

                        <div class="row mb-3">
                            <div class="col-sm-12 d-grid gap-1 mx-auto mb-3">
                                <button class="btn btn-success" type="submit" {{ $canUpdate ? '' : 'disabled' }}>
                                    Save Changes
                                    <span class="material-icons ms-1">update</span>
                                </button>
                            </div>

                            @if (is_null($user['deleted_at']))
                                @if ($canManage)
                                    <div class="col-sm-12 d-grid gap-1 mx-auto mb-3">
                                        <a class="btn btn-danger" href="{{ route('user.softDelete', $user->id) }}"
                                            confirm alert-title="Suspend account?"
                                            alert-text="Account will be suspended & you/user won't be able to
                                            login again!"
                                            spoof spoof-method="DELETE">
                                            Suspend Account
                                        </a>
                                    </div>
                                @else
                                    <div class="col-sm-12 d-grid gap-1 mx-auto mb-3" data-bs-toggle="tooltip"
                                        data-bs-placement="left" title="You are not authorized to perform this action">
                                        <a class="btn btn-danger disabled" href="#!">
                                            Suspend Account
                                        </a>
                                    </div>
                                </div>
                                @endif
                            @else
                                @if ($canManage)
                                    <div class="col-sm-12 d-grid gap-1 mx-auto mb-3">
                                        <a class="btn btn-info" href="{{ route('user.restore', $user->id) }}"
                                            confirm alert-title="Restore account?" alert-text="-" spoof
                                            spoof-method="POST">
                                            Restore
                                        </a>
                                    </div>
                                @endif

                                @if ($canDelete)
                                    <div class="col-sm-12 d-grid gap-1 mx-auto mb-3">
                                        <a class="btn btn-danger" href="{{ route('user.delete', $user->id) }}"
                                            confirm alert-title="Permanently delete this account?"
                                            alert-text="Action is irreversible!" spoof
                                            spoof-method="DELETE">
                                            Remove Permanently
                                        </a>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-12">
        <div class="card card-body border-0 shadow mb-4">
            <h5 class="mb-4">Additional Access</h5>

            <div class="row g-2 mb-2">
                <div class="col-12 col-sm-6 mb-2">
                    <h5>Departments</h5>
                    <p class="text-info fw-bold">
                        @if ($user->allowedDepartments->count() == 0)
                            <span class="text-danger">No Results / Not Applicable</span>
                        @else
                            @foreach ($user->allowedDepartments as $dept)
                                <a href="{{ route('admin.department.home', $dept->department->code) }}"
                                    target="_blank">
                                    {{ '-> ' . $dept->department->name . ($loop->last ? '' : ', ') }}
                                </a>
                            @endforeach
                        @endif
                    </p>
                </div>

                <div class="col-12 col-sm-6 mb-2">
                    <h5>Subjects</h5>
                    <p class="text-info fw-bold">-> Feature under development</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-body border-0 shadow mb-4">
            <h5 class="mb-4">Change Password</h5>

            <form class="form-floating" action="{{ route('user.changePassword', $user->id) }}" method="POST">
                {{ csrf_field() }}

                <div class="row g-2 mb-2">
                    <div class="col-12 col-sm-4 mb-2">
                        <div class="form-floating">
                            <input type="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                id="password" placeholder="Current Password" name="password"
                                required>
                            <label for="password">Current Password</label>

                            @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-12 col-sm-4 mb-2">
                        <div class="form-floating">
                            <input type="password"
                                class="form-control {{ $errors->has('new_password') ? 'is-invalid' : '' }}"
                                id="new_password" placeholder="New Password" name="new_password"
                                required>
                            <label for="new_password">New Password</label>

                            @if ($errors->has('new_password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('new_password') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-12 col-sm-4 mb-2">
                        <div class="form-floating">
                            <input type="password" class="form-control"
                                id="new_password_confirmation" placeholder="Confirm Password"
                                name="new_password_confirmation" required>
                            <label for="new_password_confirmation">Confirm Password</label>
                        </div>
                    </div>

                    <div class="col-12 d-grid gap-1 mx-auto mb-3">
                        <button class="btn btn-success" type="submit" {{ $canUpdate ? '' : 'disabled' }}>
                            <span class="material-icons me-1">verified_user</span>
                            Change Password
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script src="{{ asset('static/js/image-selector.js') }}"></script>
@endpush
