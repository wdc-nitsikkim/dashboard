{{--
    $user -> single user model (with nested relations)
--}}

@extends('layouts.admin', ['title' => 'Manage Roles - ' . $user->name])

@section('content')

@php

@endphp

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mt-3">
    <div>
        <div class="dropdown">
            <button class="btn btn-secondary d-inline-flex align-items-center me-2 dropdown-toggle"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="material-icons mx-1">add</span>
                New
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                <a class="dropdown-item d-flex align-items-center">
                    <span class="material-icons">gpp_maybe</span>
                    Role
                </a>
                <div role="separator" class="dropdown-divider my-1"></div>
                <a class="dropdown-item d-flex align-items-center"
                    href="{{ route('admin.homepage.notification.add', 'download') }}">
                    <span class="material-icons">business</span>
                    Department Access
                </a>
                <a class="dropdown-item d-flex align-items-center"
                    href="{{ route('admin.homepage.notification.add', 'notice') }}">
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

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if ($user->roles->count() == 0)
            <h5 class="text-center text-danger">This user has no roles assigned!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('users.account', $user->id)
                ])
                @endcomponent
            </p>
        @else
            <form method="POST" action="{{ route('users.savePermissions', $user->id) }}">
                {{ csrf_field() }}

                @component('components.table.main')
                    @slot('head')
                        @component('components.table.head', [
                            'items' => [
                                '#', 'Role', 'Create', 'Read', 'Update', 'Delete',
                                'Granted On'
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
                            </tr>
                        @endforeach
                    @endslot
                @endcomponent

                <button class="btn btn-primary animate-up-2 mt-2" type="submit">Save</button>
            </form>

        @endif

    </div>
</div>

@endsection
