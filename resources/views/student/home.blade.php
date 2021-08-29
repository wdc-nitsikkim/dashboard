{{--
    $student -> single student model (nested relations)
    $canView -> boolean
    $canCreate -> boolean
    $canUpdate -> boolean
--}}

@extends('layouts.admin', ['title' => 'Student Profile - ' . $student->name])

@section('content')

@include('student.partials.pageHeading', [
    'student' => $student,
    'sideBtns' => [
        'help' => '#!'
    ]
])

<div class="row row-cols-1 row-cols-md-3 g-2 mb-4">

    @if ($canView)
        <div class="col">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="material-icons me-1">preview</span>
                        View Information
                    </h5>
                    <p class="card-text">View this student's information</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('student.info.show', $student->roll_number) }}"
                        class="small text-info">View</a>
                </div>
            </div>
        </div>
    @else
        <div class="col">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="material-icons me-1">visibility_off</span>
                        View Information
                    </h5>
                    <p class="card-text">This student hasn't added their information yet!</p>
                </div>
            </div>
        </div>
    @endif

    @if ($canCreate)
        <div class="col">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="material-icons me-1">add_circle_outline</span>
                        Add Information
                    </h5>
                    <p class="card-text">Add your information. This will be used by authorized users like Office, TnP, etc..</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('student.info.add', $student->roll_number) }}"
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
                    <p class="card-text">Update previously added information</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('student.info.edit', $student->roll_number) }}"
                        class="small text-info ms-2">Update</a>
                </div>
            </div>
        </div>
    @endif

    @if (! $canCreate && ! $canUpdate)
        <div class="col">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title text-danger">
                        <span class="material-icons me-1">do_not_disturb</span>
                        Permission Error
                    </h5>
                    <p class="card-text">You don't have the required permissions to
                        <span class="fw-bolder">add/update</span> this student's information!</p>
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
                <p class="card-text">
                    View previous semester registrations or register for a new semester
                    <br>
                    <span class="fw-bolder text-danger">Feature currently unavailable</span>
                </p>
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
                <p class="card-text">View previous semester results</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('student.result', [
                        'student_by_roll_number' => $student->roll_number,
                        'semester' => CustomHelper::getSemesterFromYear($student->batch->start_year)
                    ]) }}"
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
                <p class="card-text">
                    View conversations
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
                    class="small text-info">View in GitHub
                    <span class="material-icons ms-1 fs-5">open_in_new</span>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
