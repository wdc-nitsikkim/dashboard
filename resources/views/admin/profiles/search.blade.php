{{--
    $departments -> collection of department model
--}}

@extends('layouts.admin', ['title' => 'Search profiles'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Search Profiles
    @endslot

    @slot('subheading')
        Type in your search criteria to filter results
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.profiles.search') }}"
            method="GET">

            <div class="row mb-3">
                <div class="col-sm-4 mb-2">
                    <input type="text"
                        class="form-control"
                        id="name" placeholder="Having name like"
                        name="name">
                </div>
                <div class="col-sm-4 mb-2">
                    <input type="number"
                        class="form-control"
                        id="mobile" placeholder="Having mobile number like"
                        name="mobile">
                </div>

                <div class="col-sm-4 mb-2">
                    <input type="text"
                        class="form-control"
                        id="email" placeholder="With email like"
                        name="email">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 mb-2">
                    <input type="text"
                        class="form-control"
                        id="designation" placeholder="With designation"
                        name="designation">
                </div>

                <div class="col-sm-3 mb-2">
                    <select class="form-select" name="type">
                        <option value="" selected>Type (All)</option>
                        <option value="faculty">Faculty</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <div class="col-sm-3 mb-2">
                    <select class="form-select" id="department_id" name="department_id">
                        <option value="" selected>In department (All)</option>

                        @foreach ($departments as $dept)
                            <option value="{{ $dept['id'] }}">{{ $dept['name'] }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="col-sm-3 mb-2">
                    <select class="form-select" name="trash_options">
                        <option value="" selected>Trash options (All)</option>
                        <option value="only_trash">Only trashed</option>
                        <option value="only_active">Only Active</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-9 col-md-10 mb-2">
                    <div class="input-group">
                        <span class="input-group-text">Created at</span>
                        <select class="form-select" name="created_at_compare">
                            <option value="" selected>Any time</option>
                            <option value="before">Before</option>
                            <option value="after">After</option>
                        </select>
                        <input type="date" name="created_at" class="form-control"
                            placeholder="This date">
                    </div>
                </div>
                <div class="col-sm-3 col-md-2 mb-2">
                    <div class="form-check form-check-inline form-switch">
                        <input class="form-check-input" type="checkbox" id="link" name="link" disabled>
                        <label class="form-check-label" for="link">Is Linked</label>
                    </div>
                </div>
            </div>

            @component('components.form.footerSearch')
                @slot('returnRoute')
                    {{ route('admin.profiles.show') }}
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection
