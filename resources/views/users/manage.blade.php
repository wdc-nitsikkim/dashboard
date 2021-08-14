{{--
    $user -> single user model (with nested relations)
    $roles -> array
    $departments -> collection of department model
--}}

@extends('layouts.admin', ['title' => 'Manage Roles - ' . $user->name])

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mt-3">
    <div>
        <div class="dropdown">
            <button class="btn btn-secondary d-inline-flex align-items-center me-2 dropdown-toggle"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="material-icons mx-1">add</span>
                New
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                <a class="dropdown-item d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#add-role-modal">
                    <span class="material-icons">gpp_maybe</span>
                    Role
                </a>
                <div role="separator" class="dropdown-divider my-1"></div>
                <a class="dropdown-item d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#add-dept-access-modal">
                    <span class="material-icons">business</span>
                    Department Access
                </a>
                <a class="dropdown-item d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#add-sub-access-modal">
                    <span class="material-icons">auto_stories</span>
                    Subject Access
                </a>
            </div>
        </div>
    </div>
</div>

@component('components.page.heading')
    @slot('heading')
        Manage Access - {{ $user->name }}
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'backRedirect' => route('users.account', $user->id)
        ])
    @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h5 class="mb-2">Roles & Permissions</h5>

                @if ($user->roles->count() == 0)
                    <p class="text-danger">No roles have been assigned to this account!</p>
                @else
                    <form method="POST" action="{{ route('users.manage.savePermissions', $user->id) }}">
                        {{ csrf_field() }}

                        @component('components.table.main')
                            @slot('head')
                                @component('components.table.head', [
                                    'items' => [
                                        '#', 'Role', 'Create', 'Read', 'Update', 'Delete',
                                        'Granted On', 'Revoke'
                                    ]
                                ])
                                @endcomponent
                            @endslot

                            @slot('body')
                                @foreach ($user->roles as $role)
                                    <tr>
                                        <td class="fw-bolder">{{ $loop->iteration }}</td>
                                        <td class="fw-bolder">{{ ucfirst($role->role) }}</td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="grant"
                                                    name="role[{{ $role->role }}][c]"
                                                    {{ $role->permissions->contains('permission', 'c') ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="grant"
                                                    name="role[{{ $role->role }}][r]"
                                                    {{ $role->permissions->contains('permission', 'r') ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="grant"
                                                    name="role[{{ $role->role }}][u]"
                                                    {{ $role->permissions->contains('permission', 'u') ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="grant"
                                                    name="role[{{ $role->role }}][d]"
                                                    {{ $role->permissions->contains('permission', 'd') ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $role->created_at }}
                                        </td>
                                        <td>
                                            @include('components.table.actionBtn.delete', [
                                                'href' => route('users.manage.revokeRole', $role->id)
                                            ])
                                        </td>
                                    </tr>
                                @endforeach
                            @endslot
                        @endcomponent

                        <button class="btn btn-primary animate-up-2 mt-2" type="submit" confirm
                            alert-title="Save these permissions?" alert-text="-" alert-timer="5000">
                            Save</button>
                    </form>

                @endif

            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h5 class="mb-2">Department Access</h5>

                @if ($user->allowedDepartments->count() == 0)
                    <p class="text-danger">No Results / Not Applicable</p>
                @else
                    @component('components.table.main')
                        @slot('head')
                            @component('components.table.head', [
                                'items' => [
                                    'Name', 'Granted on', 'Revoke'
                                ]
                            ])
                            @endcomponent
                        @endslot

                        @slot('body')
                            @foreach ($user->allowedDepartments as $dept)
                                <tr>
                                    <td class="fw-bolder">{{ $dept->department->name }}</td>
                                    <td>
                                        {{ $dept->created_at }}
                                    </td>
                                    <td>
                                        @include('components.table.actionBtn.delete', [
                                            'href' => route('users.manage.revokeDeptAccess', [
                                                'user_id' => $user->id,
                                                'dept_id' => $dept->department_id
                                            ])
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        @endslot
                    @endcomponent
                @endif

            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h5 class="mb-2">Subject Access</h5>
                <p class="text-info">-> Feature under development</p>
            </div>
        </div>
    </div>
</div>

@component('components.formModal', [
    'modalId' => 'add-role-modal',
    'title' => 'Add Role',
    'formAction' => route('users.manage.grantRole', $user->id),
    'submitBtnText' => 'Add'
])

    <div class="col-12 mb-2">
        <select class="form-select" id="role" name="role" required>
            <option value="" selected>Select a role</option>

            @foreach ($roles as $role)
                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
            @endforeach

        </select>
    </div>

    @slot('submitBtnAttr')
        confirm alert-title="Add role?"
        alert-text="You won't be able to manage this user for certain roles!"
    @endslot

@endcomponent

@component('components.formModal', [
    'modalId' => 'add-dept-access-modal',
    'title' => 'Give access to department',
    'formAction' => route('users.manage.grantDeptAccess', $user->id),
    'submitBtnText' => 'Add'
])

    <div class="col-12 mb-2">
        <select class="form-select" id="department_id" name="department_id" required>
            <option value="" selected>Select a department</option>

            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach

        </select>
    </div>

@endcomponent

@endsection
