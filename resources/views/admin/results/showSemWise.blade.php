{{--
    $currentSemester -> single semester model
    $semesters -> collection of semester model
    $batch -> single batch model
    $department -> single department model
    $students -> collection of student model (nested relations)
    $currentResultType -> single resultType model
    $resultTypes -> collection of resultType model
--}}

@extends('layouts.admin', ['title' => 'Students Semester-wise Results'])

@php
    $baseRouteParams = [
        'dept' => $department,
        'batch' => $batch,
        'semester' => $currentSemester,
        'result_type' => $currentResultType
    ];
@endphp

@section('content')

@component('components.page.heading')
    @slot('heading')
        Students Semester-wise Results
    @endslot

    @slot('subheading')
        <h5 class="fw-bolder">
            {{ $currentSemester == null ? 'Semester: All' : $currentSemester->name }},
            {{ $currentResultType->name }}
        </h5>

        @include('admin.students.partials.subheading', ['batch' => $batch])
        <span class="fw-bolder">{{ $department->name }}</span>

        <p class="text-info"><span class="fw-bolder">NOTE:</span> All marks are out of
            <span class="fw-bolder">{{ $currentResultType->max_marks }}</span></p>
    @endslot

    @slot('sideButtons')

        <div>
            <button class="btn btn-gray-800 d-inline-flex align-items-center dropdown-toggle mb-2" data-bs-toggle="dropdown">
                Result Type
                <span class="material-icons ms-1">keyboard_arrow_down</span>
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2">

                @foreach ($resultTypes as $type)
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('admin.results.showSemWise',
                            array_merge($baseRouteParams, [ 'result_type' => $type->id ])) }}">
                        {{ $type->name }}</a>
                @endforeach

            </div>

            <button class="btn btn-gray-800 d-inline-flex align-items-center dropdown-toggle mb-2" data-bs-toggle="dropdown">
                Semester
                <span class="material-icons ms-1">keyboard_arrow_down</span>
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2">

                @foreach ($semesters as $sem)
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('admin.results.showSemWise',
                            array_merge($baseRouteParams, [ 'semester' => $sem->id ])) }}">
                        {{ $sem->name }}</a>
                @endforeach

            </div>
        </div>
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text">
                    <span class="material-icons">search</span>
                </span>

                <input type="text" class="form-control"
                    placeholder="Find" find
                    find-in="#students > tbody > tr"
                    loader="#find-student-loader"
                    status="#find-student-status">

                <span id="find-student-loader" class="input-group-text d-none">
                    <div class="text-danger spinner-border spinner-border-sm"
                        role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </span>
            </div>
            <span class="small" id="find-student-status">Search below table</span>
        </div>

        @if ($subjects->count() == 0)
            <h5 class="mb-0 text-center text-danger">No results found!</h5>
            <h6 class="text-center">No subjects found for the chosen criteria</h6>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('admin.results.showSemWise', [
                        'dept' => $department,
                        'batch' => $batch
                    ])
                ])
                @endcomponent
            </p>
        @else
            @component('components.table.main', [
                'attr' => 'id="students"'
            ])
                @slot('head')
                    <thead class="thead-light">
                        <th class="border-0 col rounded-start">#</th>
                        <th class="border-0 col">Roll Number</th>
                        <th class="border-0 col">Name</th>

                        @foreach ($subjects as $subject)
                            <th class="border-0 col text-center
                                {{ $loop->last ? 'rounded-end' : '' }}">
                                <span class="text-wrap">{{ $subject->subject->name }}</span>
                                <br>
                                <span class="display-6 mt-1 text-info">({{ $subject->subject->code }})</span>
                            </th>
                        @endforeach

                    </thead>
                @endslot

                @slot('body')
                    @foreach ($students as $student)

                        <tr class="{{ $student->deleted_at != null ? 'text-danger' : ''}}">
                            <td>
                                <span class="fw-bolder">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                {{ $student->roll_number }}
                            </td>
                            <td>
                                {{ $student->name }}
                            </td>

                            @foreach ($subjects as $subject)
                                @php
                                    $result = $student->result->where('subject_id', $subject->subject->id)->first();
                                @endphp
                                <td class="fw-bolder text-center">
                                    {{ $result == null ? '-' : $result->score }}
                                </td>
                            @endforeach

                        </tr>

                    @endforeach
                @endslot
            @endcomponent

        @endif

    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/find.js') }}"></script>
    <script src="{{ asset('static/js/results.js') }}"></script>
@endpush
