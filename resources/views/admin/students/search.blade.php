{{--
    $departments -> collection of department model
    $batches -> collection of batch model
--}}

@extends('layouts.admin', ['title' => 'Search Students'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Search Students
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
        <form action="{{ route('admin.students.search') }}"
            method="GET">

            <div class="row mb-3">
                <div class="col-sm-4 mb-2">
                    <input type="text"
                        class="form-control"
                        id="name" placeholder="Having name like"
                        name="name">
                </div>

                <div class="col-sm-4 mb-2">
                    <select class="form-select" id="department_id" name="department_id">
                        <option value="" selected>In department (All)</option>

                        @foreach ($departments as $dept)
                            <option value="{{ $dept['id'] }}">{{ $dept['name'] }}</option>
                        @endforeach

                    </select>
                </div>
                <div class="col-sm-4 mb-2">
                    <select class="form-select" id="batch_id" name="batch_id">
                        <option value="" selected>In batch (All)</option>

                        @foreach ($batches as $batch)
                            <option value="{{ $batch['id'] }}">{{ $batch['name'] }}</option>
                        @endforeach

                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 mb-2">
                    <select class="form-select" name="trash_options">
                        <option value="" selected>Trash options (All)</option>
                        <option value="only_trash">Only trashed</option>
                        <option value="only_active">Only Active</option>
                    </select>
                </div>

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
                    {{ route('admin.students.handleRedirect') }}
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection
