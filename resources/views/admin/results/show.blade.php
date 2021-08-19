{{--
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

        @if ($students->count() == 0)
            <h5 class="text-center text-danger">No results found!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('admin.results.show', $baseRouteParams)
                ])
                @endcomponent
            </p>
        @else
            @component('components.table.main')
                @slot('head')
                    @component('components.table.head', [
                        'items' => [
                            '#', 'Roll Number', 'Name',
                            'Score (Max. 100)', 'Actions'
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

                            <td class="fw-bolder">

                                @php
                                    $score = $student->result->where('subject_id', $subject->id)->isEmpty()
                                        ? '-'
                                        : $student->result->where('subject_id', $subject->id)->first()->score;

                                    $scoreClass = 'text-danger';
                                    if ($score >= 81) {
                                        $scoreClass = 'text-success';
                                    } else if ($score >= 51) {
                                        $scoreClass = 'text-info';
                                    } else if ($score >= 33) {
                                        $scoreClass = 'text-warning';
                                    }
                                @endphp

                                <span class="{{ $scoreClass }}">{{ $score }}</span>
                            </td>

                            <td>

                                @php
                                    $routeParamsWithId = array_merge(
                                        $baseRouteParams,
                                        ['id' => $student['id']]
                                    );
                                @endphp

                                -

                            </td>
                        </tr>

                    @endforeach
                @endslot
            @endcomponent

            <nav class="my-3 d-flex justify-content-between">
                {{ $pagination }}
            </nav>
        @endif

    </div>
</div>
@endsection
