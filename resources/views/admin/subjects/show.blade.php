{{--
    $baseRoute -> string (route name)
    $subjects -> paginated collection of subject model
    $currentBatch -> single batch model
    $currentSemester -> single semester model
    $semesters -> collection of semester model
    $currentCourse -> single course model
    $courses -> collection of course model
    $currentDepartment -> single department model
    $pagination -> pagination links view
--}}

@extends('layouts.admin', ['title' => 'Subject List'])

@section('content')

@php
    $baseRouteParams = [
        'dept' => $currentDepartment,
        'batch' => $currentBatch,
        'course' => $currentCourse,
        'semester' => $currentSemester
    ];
@endphp

@component('components.page.heading')
    @slot('heading')
        List of Subjects
        @slot('subheading')
            <h5>
                <span class="fw-bolder">{{ $currentCourse == null ? 'All Courses' : $currentCourse->name }}</span>,
                {{ $currentSemester == null ? 'All Semesters' : $currentSemester->name }}
            </h5>
            <h6>Subjects registered for <span class="fw-bolder">
                {{ $currentBatch->name }}</span></h6>
        @endslot
    @endslot

    @slot('sideButtons')

        <div>
            @include('partials.pageSideBtns', [
                'print' => '.table-responsive'
            ])

            @include('components.inline.dropdownBtn', [
                'all' => route($baseRoute, array_merge($baseRouteParams, [ 'course' => null ])),
                'btnText' => 'Course',
                'iterator' => $courses,
                'route' => $baseRoute,
                'routeParam' => 'course',
                'routeKey' => 'code',
                'displayKey' => 'name',
                'baseParams' => $baseRouteParams
            ])

            @include('components.inline.dropdownBtn', [
                'btnText' => 'Semester',
                'iterator' => $semesters,
                'route' => $baseRoute,
                'routeParam' => 'semester',
                'routeKey' => 'id',
                'displayKey' => 'name',
                'baseParams' => $baseRouteParams
            ])
        </div>
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if ($subjects->count() == 0)
            <h5 class="text-center text-danger">No results found!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('admin.subjects.show', [
                        'dept' => $currentDepartment,
                        'batch' => $currentBatch
                    ])
                ])
                @endcomponent
            </p>
        @else
            @component('components.table.main')
                @slot('head')
                    @component('components.table.head', [
                        'items' => [
                            '#', 'Course', 'Semester', 'Code', 'Name', 'Credit'
                        ]
                    ])
                    @endcomponent
                @endslot

                @slot('body')
                    @foreach ($subjects as $subject)

                        <tr class="{{ $subject['deleted_at'] != null ? 'text-danger' : ''}}">
                            <td>
                                <span class="text-primary fw-bold">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                {{ $courses->where('id', $subject->subject->course_id)->first()->name }}
                            </td>
                            <td>
                                {{ $currentSemester->name }}
                            </td>
                            <td class="fw-bolder">
                                {{ $subject->subjectCode }}
                            </td>
                            <td>
                                <span class="fw-bolder">
                                    {{ $subject->subject->name }}</span>
                            </td>
                            <td class="fw-bolder">
                                {{ $subject->credit }}
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
