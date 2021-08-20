{{--
    $canUpdate -> boolean
    $department -> single department model
    $batch -> single batch model
    $subject -> single subject model
    $students -> collection of student model (nested relations)
    $pagination -> pagination links view
--}}

@extends('layouts.admin', ['title' => 'Student Results - ' . strtoupper($subject->code)])

@section('content')

@php
    $redirectHandler = 'admin.results.handleRedirect';

    $baseRouteParams = [
        'dept' => $department,
        'batch' => $batch,
        'subject' => $subject
    ];
@endphp

@component('components.page.heading')
    @slot('heading')
        Student Results
    @endslot

    @slot('subheading')
        @include('admin.students.partials.subheading', ['batch' => $batch])

        {{ $department->name }}
        <br>
        <span class="fw-bolder">{{ $subject->name }} ({{ strtoupper($subject->code) }})</span>
        <div class="mt-1 text-info">
            <h6 class="fw-bolder">General Information:</h6>
            <ul class="mt--1">
                <li>All marks are out of 100</li>

                @if ($canUpdate)
                    <li>For absentees, leave marks as blank <span class="fw-bolder">(not 0)</span></li>
                    <li>Result for absent students are not kept, i.e., students with no result are automatically
                        treated as absent</li>
                    <li>Save the marks using the button given below before navigating to some other page</li>
                @endif

            </ul>
        </div>
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
            'searchRedirect' => '#!',
            'deptRedirect' => $redirectHandler,
            'batchRedirect' => $redirectHandler,
            'subjectRedirect' => $redirectHandler
        ])
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

                <div class="input-group-text bg-gray-100">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox"
                            {{ $canUpdate ? 'id=toggle-edit' : 'disabled' }}>
                        <label class="form-check-label mb-0" for="toggle-edit">
                            Edit
                        </label>
                    </div>
                </div>
            </div>
            <span class="small" id="find-student-status">Search below table</span>
        </div>

        @if ($students->count() == 0)
            <h5 class="text-center text-danger">No results found!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('admin.results.show', $baseRouteParams)
                ])
                @endcomponent
            </p>
        @else

            <form action="{{ route('admin.results.save', $baseRouteParams) }}"
                method="POST">

                {{ csrf_field() }}

                @component('components.table.main', [
                    'attr' => 'id="students"'
                ])
                    @slot('head')
                        @component('components.table.head', [
                            'items' => [
                                '#', 'Roll Number', 'Name',
                                'Marks', 'Last Updated'
                            ]
                        ])
                        @endcomponent
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

                                @php
                                    $result = $student->result->where('subject_id', $subject->id)->isEmpty()
                                        ? null
                                        : $student->result->where('subject_id', $subject->id)->first();

                                    $scoreClass = 'text-danger';
                                    $score = '';

                                    if ($result != null && $result->deleted_at == null) {
                                        $score = $result->score ?? '';
                                        if ($score >= 81) {
                                            $scoreClass = 'text-success';
                                        } else if ($score >= 51) {
                                            $scoreClass = 'text-info';
                                        } else if ($score >= 33) {
                                            $scoreClass = 'text-primary';
                                        }
                                    }
                                @endphp

                                <td class="fw-bolder">
                                    <span class="{{ $scoreClass }}">{{ $score == '' ? '-' : $score }}</span>
                                    <input type="number" class="form-control d-none" value="{{ $score }}"
                                        name="result[{{ $student->id }}]" min="0" max="100">
                                </td>

                                <td>{{ $result ? $result->updated_at : '-' }}</td>
                            </tr>

                        @endforeach
                    @endslot
                @endcomponent

                @if ($canUpdate)
                    @component('components.form.footerEdit', [
                        'submitBtnTxt' => 'Save'
                    ])
                        @slot('submitBtnAttr')
                            confirm alert-title="Save these marks?"
                            alert-text="Previous marks will be lost"
                        @endslot

                        @slot('returnRoute')
                            {{ route('admin.results.show', $baseRouteParams) }}
                        @endslot
                    @endcomponent
                @endif

                <nav class="my-3 d-flex justify-content-between">
                    {{ $pagination }}
                </nav>

            </form>

        @endif

    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/find.js') }}"></script>
    <script src="{{ asset('static/js/results.js') }}"></script>
@endpush
