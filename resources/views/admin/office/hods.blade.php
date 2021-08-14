{{--
    $departments -> collection of department model
    $hods -> collection of hod model (with nested relations)
--}}

@extends('layouts.admin', ['title' => 'Head of Departments'])

@section('content')

@can('create', 'App\\Models\Hod')
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
                        data-bs-toggle="modal" data-bs-target="#add-hod-modal">
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
        Currently assigned Head of Departments
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

                @if ($hods->count() == 0)
                    <p class="text-danger">None profiles have been assigned as HoD</p>
                @else
                    @component('components.table.main')
                        @slot('head')
                            @component('components.table.head', [
                                'items' => [
                                    '#', 'Department', 'HoD', 'Remove'
                                ]
                            ])
                            @endcomponent
                        @endslot

                        @slot('body')
                            @foreach ($hods as $hod)
                                <tr>
                                    <td class="fw-bolder">{{ $loop->iteration }}</td>
                                    <td>{{ $hod->department->name }}</td>
                                    <td class="fw-bolder">
                                        {{ $hod->profile->name }}
                                    </td>
                                    <td>

                                        @can('update', 'App\\Models\Hod')
                                            @include('components.table.actionBtn.delete', [
                                                'href' => route('admin.office.hods.remove', $hod->department->id)
                                            ])
                                        @endcan

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
    'modalId' => 'add-hod-modal',
    'title' => 'Assign HoD',
    'formAction' => route('admin.office.hods.assign'),
    'submitBtnText' => 'Assign'
])

    <div class="row mb-2">
        <div class="col-12 mb-2">
            <select class="form-select" name="department_id" required>
                <option value="" selected>Select a department</option>

                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach

            </select>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-9 mb-2">
            <input type="text" class="form-control" placeholder="Type a name to search"
                dynamic-list="profile-list" tmp-name="tmp_profile_id" autofill="profile_id"
                endpoint="{{ route('api.searchProfilesByName') }}">
        </div>
        <div class="col-3 mb-2">
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
        confirm alert-title="Assign selected profile as HoD?"
        alert-text="-"
    @endslot

@endcomponent

@endsection

@push('scripts')
    <script src="{{ asset('static/js/dynamic-list.js') }}"></script>
@endpush
