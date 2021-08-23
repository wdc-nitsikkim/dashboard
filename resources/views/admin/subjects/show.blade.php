{{--
    $subjects -> paginated collection of subject model
    $semesters -> array
    $currentSemester -> int
    $currentDepartment -> single department model
    $departments -> collection of department model
    $pagination -> pagination links view
--}}

@extends('layouts.admin', ['title' => 'Subject List'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        List of Subjects
        @slot('subheading')
            <span class="fw-bolder">{{ $currentDepartment->name }}</span>,
            Semester: <span class="fw-bolder">{{ $currentSemester == null ? 'All' : $currentSemester }}
                </span>
        @endslot
    @endslot

    @slot('sideButtons')

        <div>
            <button class="btn btn-gray-800 d-inline-flex align-items-center dropdown-toggle mb-2" data-bs-toggle="dropdown">
                Department
                <span class="material-icons ms-1">keyboard_arrow_down</span>
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2">

                @foreach ($departments as $dept)
                    @if ($dept->id == $currentDepartment->id)
                        @continue
                    @endif

                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('admin.subjects.show', [
                            'dept' => $dept->code
                        ]) }}">
                        {{ $dept->name }}</a>
                @endforeach

            </div>

            <button class="btn btn-gray-800 d-inline-flex align-items-center dropdown-toggle mb-2" data-bs-toggle="dropdown">
                Semester
                <span class="material-icons ms-1">keyboard_arrow_down</span>
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2">

                <a class="dropdown-item d-flex align-items-center"
                    href="{{ route('admin.subjects.show', [
                        'dept' => $currentDepartment
                    ]) }}">
                    All
                </a>

                @foreach ($semesters as $sem)
                    @if ($sem == $currentSemester)
                        @continue
                    @endif

                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('admin.subjects.show', [
                            'dept' => $currentDepartment,
                            'semester' => $sem
                        ]) }}">
                        {{ ucwords($sem) }}</a>
                @endforeach

            </div>
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
                            '#', 'Code', 'Name', 'Credit'
                        ]
                    ])
                    @endcomponent
                @endslot

                @slot('body')
                    @foreach ($subjects['data'] as $subject)
                        <tr class="{{ $subject['deleted_at'] != null ? 'text-danger' : ''}}">
                            <td>
                                <span class="text-primary fw-bold">{{ $loop->iteration }}</span>
                            </td>
                            <td>
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
