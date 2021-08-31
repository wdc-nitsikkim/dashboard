{{--
    $batch -> single batch model
    $batches -> collection pf batch model
    $semesters -> collection of semester model
    $department -> single department model
--}}

@extends('layouts.admin', ['title' => 'Register Subjects'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Register Subjects
    @endslot

    @slot('subheading')
        <h5>Department of {{ $department->name }}</h5>
        <h5>{{ $batch->course->name }}, {{ $batch->name }}</h5>

        -> Duplicate entries are not allowed!
        <br>
        -> Only the latest 10 batches are shown in the dropdown list. If you wish to select an older batch, please
        click on the <code class="fw-bolder mx-1">Change Batch</code> button.
        <br>
        <span class="text-info fw-bolder">-> Either way the page will be refreshed!</span>
    @endslot

    @slot('sideButtons')

        <div>

            <button class="btn btn-outline-info d-inline-flex align-items-center dropdown-toggle ms-1
                mb-2" data-bs-toggle="dropdown">
                Batch
                <span class="material-icons ms-1">keyboard_arrow_down</span>
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2">

                @foreach ($batches as $tmpBatch)
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('admin.subjects.addReg', [
                            'dept' => $department,
                            'batch' => $tmpBatch
                        ]) }}">
                        {{ $tmpBatch->course->name }} - {{ $tmpBatch->name }}
                    </a>
                @endforeach

            </div>

            @include('partials.pageSideBtns', [
                'batchRedirect' => route('admin.subjects.addReg', $department)
            ])

        </div>

    @endslot
@endcomponent

<div class="row mb-4">
    <div class="col-12 col-lg-7 col-sm-12">
        <div class="card card-body border-0 shadow mb-4">
            <form class="form-floating"
                action="{{ route('admin.subjects.saveNewReg', [
                    'dept' => $department,
                    'batch' => $batch
                ]) }}"
                method="POST" id="bulk-subjects-form">
                {{ csrf_field() }}

                @component('components.table.main', [
                    'attr' => 'id = "subjects-table"'
                ])
                    @slot('head')
                        @component('components.table.head', [
                            'items' => [ 'Type', 'Code', 'Name', '-' ]
                        ])
                        @endcomponent
                    @endslot

                    @slot('body')
                            <tr row-clone>
                                <td class="col-4">
                                    <select class="form-control form-select" name="semester_id[]" required>
                                        @foreach ($semesters as $sem)
                                            <option value="{{ $sem->id }}"
                                                {{ $loop->first ? 'selected' : '' }}>
                                                {{ $sem->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="col-3">
                                    <input type="number" class="form-control" placeholder="Credit"
                                        name="credit[]" min="1" max="6" value="2" required>
                                </td>
                                <td class="col-4">
                                    <input type="text" class="form-control" placeholder="Subject ID"
                                        name="subject_id[]" required>
                                </td>
                                <td class="col-1">
                                    <span class="material-icons cur-pointer text-danger" delete-row>delete</span>
                                </td>
                            </tr>

                    @endslot
                @endcomponent

                <div class="row mt-2 mb-3">
                    <div class="col text-end">
                        <button class="btn btn-outline-primary" type="button"
                            id="add-row">
                            Add Row
                            <span class="material-icons ms-1">add</span>
                        </button>
                    </div>
                </div>

                @component('components.form.footerAdd')
                    @slot('returnRoute')
                        {{ route('admin.department.home', $department) }}
                    @endslot

                    @slot('submitBtnTxt')
                        Register Subjects
                    @endslot
                @endcomponent

            </form>
        </div>
    </div>

    <div class="col-12 col-lg-5 col-sm-12">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card card-body border-0 shadow">
                    <h5 class="fw-bolder">Find Subjects</h5>

                    <div class="row mb-3 mt-1">
                        <div class="col-12 mb-2">
                            <label for="department">Select Department</label>
                            <select class="form-control form-select" name="department" required>
                                <option value="" selected>All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">
                                        {{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    placeholder="Type a name to fetch subjects from database"
                                    dynamic-list="subjects-list" tmp-name="tmp_subject_id" autofill=""
                                    endpoint="{{ route('api.searchSubject') }}" append='["department"]'
                                    emitevent="subjectChosen">

                                <span id="subjects-list-loader" class="input-group-text d-none">
                                    <div class="text-danger spinner-border spinner-border-sm"
                                        role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <span class="material-icons">search</span>
                                </span>

                                <input type="text" class="form-control"
                                    placeholder="Find (Local search)" find
                                    find-in="#subjects-list label.list-group-item"
                                    loader="#find-subject-loader"
                                    status="#find-subjects-status">

                                <span id="find-subject-loader" class="input-group-text d-none">
                                    <div class="text-danger spinner-border spinner-border-sm"
                                        role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </span>
                            </div>
                            <p class="small text-info ms-1" id="find-subjects-status">-</p>
                        </div>

                        <div class="col-12 mb-2">
                            <div id="subjects-list" class="list-group list-group-flush">
                                <span class="text-info small">
                                    Type a name to search
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('static/js/json-formerror-handler.js') }}"></script>
    <script src="{{ asset('static/js/dynamic-list.js') }}"></script>
    <script src="{{ asset('static/js/find.js') }}"></script>
    <script src="{{ asset('static/js/subjects.js') }}"></script>
@endpush
