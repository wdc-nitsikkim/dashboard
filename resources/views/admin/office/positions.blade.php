{{--
    $positions -> collection of position model (with nested relations)
--}}

@extends('layouts.admin', ['title' => 'Position of Responsibilities'])

@section('content')

@can('create', 'App\\Models\Position')
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
                        data-bs-toggle="modal" data-bs-target="#add-por-modal">
                        <span class="material-icons">perm_identity</span>
                        Assign
                    </a>
                </div>
            </div>
        </div>
    </div>
@endcan

@component('components.page.heading')
    @slot('heading')
        Positions of Responsibilities
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">

                @if ($positions->count() == 0)
                    <p class="text-danger">None profiles hold any position of responsibilites</p>
                @else
                    @component('components.table.main')
                        @slot('head')
                            @component('components.table.head', [
                                'items' => [
                                    '#', 'Name', 'Position', 'Mobile',
                                    'Email', 'Remove'
                                ]
                            ])
                            @endcomponent
                        @endslot

                        @slot('body')
                            @foreach ($positions as $position)
                                <tr>
                                    <td class="fw-bolder">{{ $loop->iteration }}</td>
                                    <td class="fw-bolder">{{ $position->profile->name }}</td>
                                    <td class="fw-bolder">
                                        {{ $position->position }}
                                    </td>
                                    <td>
                                        @component('components.inline.anchorLink', [
                                            'align' => '',
                                            'route' => 'tel:' . $position->mobile,
                                            'classes' => 'text-info',
                                            'tooltip' => false
                                        ])
                                            {{ $position->mobile }}
                                        @endcomponent
                                    </td>
                                    <td>
                                        @component('components.inline.anchorLink', [
                                            'align' => '',
                                            'route' => 'mailto:' . $position->email,
                                            'classes' => 'text-info',
                                            'tooltip' => false
                                        ])
                                            {{ $position->email }}
                                        @endcomponent
                                    </td>
                                    <td>
                                        @include('components.table.actionBtn.delete', [
                                            'href' => route('admin.office.positions.remove', $position->id)
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

</div>

@component('components.formModal', [
    'modalId' => 'add-por-modal',
    'title' => 'Assign Position of Responsibility',
    'formAction' => route('admin.office.positions.assign'),
    'submitBtnText' => 'Assign'
])

    <div class="row mb-2">
        <div class="col-12 mb-2">
            <input type="text" id="position" name="position" class="form-control"
                placeholder="Position of Responsibility" required>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12 mb-2">
            <input type="email" name="email" class="form-control"
                placeholder="Email" required>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12 mb-2">
            <input type="number" name="mobile" class="form-control"
                placeholder="Mobile" required>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-sm-8 mb-2">
            <input type="text" class="form-control" placeholder="Type a name to search"
                dynamic-list="profile-list" tmp-name="tmp_profile_id" autofill="profile_id"
                endpoint="{{ route('api.searchProfilesByName') }}">
        </div>
        <div class="col-sm-4 mb-2">
            <input type="number" id="profile_id" name="profile_id" class="form-control"
                placeholder="Profile ID" required>
        </div>
        <div class="col-12 mt-2">
            <div class="d-flex justify-content-between">
                <h6>Matched Profiles:</h6>
                <div id="profile-list-loader" class="d-none text-danger spinner-border spinner-border-sm"
                    role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div id="profile-list" class="list-group list-group-flush">
                <span class="text-info small">
                    Type a name to search
                </span>
            </div>

        </div>
    </div>

    @slot('submitBtnAttr')
        confirm alert-title="Assign PoR to this profile?"
        alert-text="-"
    @endslot

@endcomponent

@endsection

@push('scripts')
    <script src="{{ asset('static/js/dynamic-list.js') }}"></script>
@endpush
