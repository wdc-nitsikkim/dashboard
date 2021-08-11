{{--
    $users -> collection of user model (with 'roles' relation)
    $pagination -> pagination links view
--}}

@extends('layouts.admin', ['title' => 'Accounts'])

@section('content')

@php
    $userModel = 'App\\Models\\User';
@endphp

@component('components.page.heading')
    @slot('heading')
        Registered Accounts
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
            'searchRedirect' => ''
        ])
    @endslot
@endcomponent

<div class="row g-0 mb-3">
    <div class="col alert alert-info" role="alert">
        View your account
        <a href="{{ route('users.account', Auth::id()) }}" target="_blank">
            <span class="material-icons">open_in_new</span>
        </a>
    </div>
</div>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2 mb-3">

    @if ($users->count() == 0)
        <div class="col-12">
            <h5 class="text-center text-danger">No results found!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('root.default')
                ])
                @endcomponent
            </p>
        </div>
    @else
        @foreach ($users as $user)
            <div class="col">

                @php
                    $roleList = '';
                @endphp

                @foreach ($user->roles as $role)
                    @php
                        $roleList .= '<span class="badge bg-tertiary text-black me-1">'
                            . strtoupper($role->role) . '</span>'
                    @endphp
                @endforeach

                @component('components.card', [
                    'name' => $user->name,
                    'image' => $user->image,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'secondaryText' => $roleList
                ])

                    <div class="card-footer d-flex justify-content-end p-2 mx-1">

                        @component('components.inline.anchorLink', [
                            'route' => route('users.account', $user->id),
                            'icon' => 'visibility',
                            'tooltip' => 'View',
                            'scale' => true
                        ])
                            @slot('attr')
                                target="_blank"
                            @endslot
                        @endcomponent

                        @if ($user->deleted_at == null && $canManage)
                            @component('components.inline.anchorLink', [
                                'route' => route('users.softDelete', $user->id),
                                'icon' => 'person_remove',
                                'classes' => 'text-danger',
                                'tooltip' => 'Suspend Account',
                                'scale' => true
                            ])
                                @slot('attr')
                                    confirm alert-title="Suspend this account?"
                                    alert-text="User won't be able to login after this!"
                                    alert-timer="3600" spoof spoof-method="DELETE"
                                @endslot
                            @endcomponent
                        @else
                            @if ($canManage)
                                @include('components.table.actionBtn.restore', [
                                    'href' => route('users.restore', $user->id)
                                ])
                            @endif

                            @if ($canDelete)
                                @include('components.table.actionBtn.delete', [
                                    'href' => route('users.delete', $user->id)
                                ])
                            @endif
                        @endif

                    </div>

                @endcomponent

            </div>
        @endforeach
    @endif

</div>

<nav class="my-3 d-flex justify-content-between">
    {{ $pagination }}
</nav>

@endsection
