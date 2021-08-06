@extends('layouts.admin', ['title' => 'Profiles'])

@section('content')

@php
    $profileModel = 'App\\Models\\Profile';
@endphp

@can('create', $profileModel)
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
                        href="{{ route('admin.profiles.add') }}">
                        <span class="material-icons">person_add</span>
                        Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
@endcan

@component('components.page.heading')
    @slot('heading')
        Registered Profiles
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

@if ($ownProfile)
    <div class="row g-0 mb-3">
        <div class="col alert alert-success" role="alert">
            You have a profile linked to your account. Manage it directly from
            <a href="{{ route('admin.profiles.edit', $ownProfile) }}" target="_blank">here
                <span class="material-icons">open_in_new</span>
            </a>
        </div>
    </div>
@elseif (Auth::user()->hasRole('hod', 'faculty', 'staff'))
    <div class="row g-0 mb-3">
        <div class="col alert alert-info" role="alert">
            Looks like you haven't created a profile yet. Create now using the
            <a class="fw-bolder px-1 text-info"
                href="{{ route('admin.profiles.add') }}">New</a> button.
        </div>
    </div>
@endif

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2 mb-3">

    @foreach ($profiles['data'] as $profile)
        @php
            $profileId = $profile['id'];
        @endphp

        <div class="col">
            @component('components.card', [
                'name' => $profile['name'],
                'type' => $profile['type'],
                'designation' => $profile['designation'],
                'image' => $profile['image'],
                'email' => $profile['email'],
                'mobile' => $profile['mobile'],
                'department' => $profile['department']['name']
            ])

                @if (Auth::user()->can('update', [$profileModel, $profileId])
                    || Auth::user()->can('delete', [$profileModel, $profileId]))

                    <div class="card-footer d-flex justify-content-end p-2 mx-1">

                        @if ($profile['deleted_at'] == null)
                            @can('update', [$profileModel, $profileId])
                                @include('components.table.actionBtn.edit', [
                                    'href' => route('admin.profiles.edit', $profileId)
                                ])
                                @include('components.table.actionBtn.trash', [
                                    'href' => route('admin.profiles.softDelete', $profileId)
                                ])
                            @endcan
                        @else
                            @can('update', [$profileModel, $profileId])
                                @include('components.table.actionBtn.restore', [
                                    'href' => route('admin.profiles.restore', $profileId)
                                ])
                            @endcan

                            @can('delete', [$profileModel, $profileId])
                                @include('components.table.actionBtn.delete', [
                                    'href' => route('admin.profiles.delete', $profileId)
                                ])
                            @endcan
                        @endif

                    </div>

                @endif
            @endcomponent
        </div>
    @endforeach

</div>

<nav class="my-3 d-flex justify-content-between">
    {{ $pagination }}
</nav>

@endsection
