{{--
    $student -> single student model (nested relations)
    $canCreate -> boolean
    $canUpdate -> boolean
--}}

@extends('layouts.admin', ['title' => 'Student Profile - ' . $student->name])

@section('content')

@component('components.page.heading')
    @slot('heading')
        {{ $student->name }} ({{ $student->roll_number }})
    @endslot

    @slot('subheading')
        <h5>
            {{ 'Department of ' . $student->department->name }}
            <br>
            @include('admin.students.partials.subheading', [
                'batch' => $student->batch
            ])
        </h5>
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
        ])
    @endslot
@endcomponent

<div class="row row-cols-1 row-cols-md-3 g-2 mb-4">

    @if ($canCreate)
        <div class="col">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="material-icons me-1">add_circle_outline</span>
                        Add Information
                    </h5>
                    <p class="card-text">You haven't added your information yet!</p>
                </div>
                <div class="card-footer">
                    <a href="#!"
                        class="small text-info">Add now</a>
                </div>
            </div>
        </div>
    @elseif ($canUpdate)
        <div class="col">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="material-icons me-1">drive_file_rename_outline</span>
                        Update
                    </h5>
                    <p class="card-text">Update your previously added information</p>
                </div>
                <div class="card-footer">
                    <a href="#!"
                        class="small text-info">Update</a>
                </div>
            </div>
        </div>
    @else
        <div class="col">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title text-danger">
                        <span class="material-icons me-1">do_not_disturb</span>
                        Permission Error
                    </h5>
                    <p class="card-text">You don't have the required permissions to
                        <span class="fw-bolder">add/update</span> your information</p>
                </div>
            </div>
        </div>
    @endif

    <div class="col">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="material-icons me-1">app_registration</span>
                    Semester Registrations
                </h5>
                <p class="card-text">View your previous semester registrations or register for a new semester</p>
            </div>
            <div class="card-footer">
                <a href="#!"
                    class="small text-info">Go to page</a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="material-icons me-1">emoji_events</span>
                    Results
                </h5>
                <p class="card-text">View your previous semester results</p>
            </div>
            <div class="card-footer">
                <a href="#!"
                    class="small text-info">Go to page</a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="material-icons me-1">chat_bubble_outline</span>
                    Messages/queries
                </h5>
                <p class="card-text">View your conversations
                    <br>
                    <span class="fw-bolder text-danger">Feature currently unavailable</span>
                </p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card shadow-lg h-100 gradient-bg">
            <div class="card-body">
                <h5 class="card-title">
                    <img src="{{ asset('static/images/github-octocat.svg') }}" alt="github-logo"
                            class="inline-svg scale-on-hover">
                    Contribute
                </h5>
                <p class="card-text">Contribute to this project & make it even better 😀</p>
            </div>
            <div class="card-footer">
                <a href="https://github.com/wdc-nitsikkim" target="_blank"
                    class="small text-info">View GitHub
                    <span class="material-icons ms-1 fs-5">open_in_new</span>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
