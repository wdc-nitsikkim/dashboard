{{--
    $notifications -> paginated (in array) collection of HomepageNotification model
    $pagination -> pagination links view
    $canUpdate -> boolean
    $canDelete -> boolean
--}}

@extends('layouts.admin', ['title' => 'Search Notifications'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Search Notifications
    @endslot

    @slot('subheading')
        Type in your search criteria to filter results
    @endslot

    @slot('sideButtons')
        <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
            <span class="material-icons mx-1">help</span>
        </a>
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('admin.homepage.notification.search') }}"
            method="GET">
            {{-- {{ csrf_field() }} --}}

            <div class="row mb-3">
                <div class="col-sm-4 mb-2">
                    <input type="text"
                        class="form-control"
                        id="display_text" placeholder="Containing this text"
                        name="display_text">
                </div>

                <div class="col-sm-4 mb-2">
                    <select class="form-select" id="type" name="type">
                        <option value="" selected>Of type (All)</option>
                        <option value="announcement">Announcement</option>
                        <option value="download">Download</option>
                        <option value="notice">Notice</option>
                        <option value="tender">Tender</option>
                    </select>
                </div>
                <div class="col-sm-4 mb-2">
                    <input type="text" class="form-control" placeholder="With link like"
                        name="link">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-4 mb-2">
                    <select class="form-select" name="status">
                        <option value="" selected>Having status (All)</option>
                        <option value="active">Active</option>
                        <option value="hidden">Hidden</option>
                    </select>
                </div>
                <div class="col-4 mb-2">
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
                    {{ route('admin.homepage.notification.show') }}
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection
