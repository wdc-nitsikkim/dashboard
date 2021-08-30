{{--
    $baseRoute -> string (route name)
    $subjects -> paginated collection of subject model
    $nestedSubject -> boolean
    $currentSemester -> single semester model
    $semesters -> collection of semester model
    $currentDepartment -> single department model
    $departments -> collection of department model
    $currentCourse -> single course model
    $courses -> collection of course model
    $pagination -> pagination links view
--}}

@extends('layouts.admin', ['title' => 'Subject List'])

@section('content')

@php
    $baseRouteParams = [
        'dept' => $currentDepartment,
        'course' => $currentCourse,
        'semester' => $currentSemester
    ];
@endphp

@component('components.page.heading')
    @slot('heading')
        List of Subjects
        @slot('subheading')
            <h5>
                <span class="text-info">
                    {{ $currentDepartment == null ? 'All Departments' : 'Department of '
                        . $currentDepartment->name }}</span>
                <br>
                <span class="fw-bolder">{{ $currentCourse == null ? 'All Courses' : $currentCourse->name }}</span>,
                {{ $currentSemester == null ? 'All Semesters' : $currentSemester->name }}
            </h5>
            <h6>{{ $secondaryText ?? '' }}</h6>
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
                'all' => route($baseRoute, array_merge($baseRouteParams, [ 'dept' => null ])),
                'btnText' => 'Department',
                'iterator' => $departments,
                'route' => $baseRoute,
                'routeParam' => 'dept',
                'routeKey' => 'code',
                'displayKey' => 'name',
                'baseParams' => $baseRouteParams
            ])

            @include('components.inline.dropdownBtn', [
                'all' => route($baseRoute, array_merge($baseRouteParams, [ 'semester' => null ])),
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

        @if (count($subjects['data']) == 0)
            <h5 class="text-center text-danger">No results found!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('admin.subjects.show', [
                        'dept' => $currentDepartment
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
                    @foreach ($subjects['data'] as $subject)
                        @isset($nestedSubject)
                            @php
                                $subject = $subject['subject'];
                            @endphp
                        @endif

                        <tr class="{{ $subject['deleted_at'] != null ? 'text-danger' : ''}}">
                            <td>
                                <span class="text-primary fw-bold">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                {{ $courses->where('id', $subject['course_id'])->first()->name }}
                            </td>
                            <td>
                                {{ $semesters->where('id', $subject['semester_id'])->first()->name }}
                            </td>
                            <td class="fw-bolder">
                                {{ strtoupper($subject['code']) }}
                            </td>
                            <td>
                                <span class="fw-bolder">
                                    {{ $subject['name'] }}</span>
                            </td>
                            <td class="fw-bolder">
                                {{ $subject['credit'] }}
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
