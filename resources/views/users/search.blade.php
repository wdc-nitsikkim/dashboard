{{--
    $roles -> array
--}}

@extends('layouts.admin', ['title' => 'Search Users'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Search Users
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
        <form action="{{ route('users.search') }}"
            method="GET">

            <div class="row mb-3">
                <div class="col-sm-4 mb-2">
                    <input type="text"
                        class="form-control"
                        id="name" placeholder="Having name like"
                        name="name">
                </div>

                <div class="col-sm-4 mb-2">
                    <select class="form-select" id="role" name="role">
                        <option value="" selected>With role (All)</option>

                        @foreach ($roles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="col-sm-4 mb-2">
                    <select class="form-select" name="trash_options">
                        <option value="" selected>Trash options (All)</option>
                        <option value="only_trash">Only trashed</option>
                        <option value="only_active">Only Active</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
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
            </div>

            @component('components.form.footerSearch')
                @slot('returnRoute')
                    {{ route('users.show') }}
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection
