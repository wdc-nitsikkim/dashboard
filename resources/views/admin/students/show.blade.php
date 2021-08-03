@extends('layouts.admin', ['title' => 'View Students - ' . $department['name']])

@section('content')

@php
    $batchModel = 'App\\Models\\Batch';
    $studentModel = 'App\\Models\\Student';
    $redirectHandler = 'students.handleRedirect';

    $baseRouteParams = [
        'dept' => $department,
        'batch' => $batch
    ];
@endphp

@can('create', [$studentModel, $department])
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mt-3">
        <div>
            <div class="dropdown">
                <button class="btn btn-secondary d-inline-flex align-items-center me-2 dropdown-toggle"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="material-icons mx-1">add</span>
                    New
                </button>

                <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('students.add', $baseRouteParams) }}">
                        <span class="material-icons">face</span>
                        Student
                    </a>
                    <a class="dropdown-item d-flex align-items-center"
                        href="#!">
                        <span class="material-icons">group_add</span>
                        Bulk Add
                    </a>
                </div>
            </div>
        </div>
    </div>
@endcan

@component('components.page.heading')
    @slot('heading')
        Student List - {{ $batch['name'] }}
    @endslot

    @slot('subheading')
        @include('admin.students.partials.subheading', ['batch' => $batch])

        {{ $department['name'] }}
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
            'deptRedirect' => $redirectHandler,
            'batchRedirect' => $redirectHandler
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if (count($students['data']) == 0)
            <h5 class="text-center text-danger">No results found!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                        'href' => route('students.show', $baseRouteParams)
                    ])
                @endcomponent
            </p>
        @else
            @component('components.table.main')
                @slot('head')
                    @component('components.table.head', [
                            'items' => [
                                '#', 'Roll Number', 'Name',
                                'Email', 'Actions'
                            ]
                        ])
                    @endcomponent
                @endslot

                @slot('body')
                    @foreach ($students['data'] as $student)

                        <tr class="{{ $student['deleted_at'] != null ? 'text-danger' : ''}}">
                            <td>
                                <span class="fw-bolder">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                {{ strtoupper($student['roll_number']) }}
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px"
                                    data-bs-toggle="tooltip"
                                    title="{{ ucwords($student['name']) }}">
                                    {{ ucwords($student['name']) }}</span>
                            </td>
                            <td>
                                <a class="d-inline-block text-truncate text-info" style="max-width: 200px"
                                    href="mailto:{{ strtolower($student['email']) }}">
                                    {{ strtolower($student['email']) }}</a>
                            </td>

                            <td>

                                @php
                                    $routeParamsWithId = array_merge(
                                        $baseRouteParams,
                                        ['id' => $student['id']]
                                    );
                                @endphp

                                @if ($student['deleted_at'] == null)
                                    @can('update', [$studentModel, $department])
                                        @include('components.table.actionBtn.edit', [
                                            'href' => route('students.edit', $routeParamsWithId)
                                        ])
                                        @include('components.table.actionBtn.trash', [
                                            'href' => route('students.softDelete', $routeParamsWithId)
                                        ])
                                    @endcan
                                @else
                                    @can('update', [$studentModel, $department])
                                        @include('components.table.actionBtn.restore', [
                                            'href' => route('students.restore', $routeParamsWithId)
                                        ])
                                    @endcan

                                    @can('delete', [$studentModel, $department])
                                        @include('components.table.actionBtn.delete', [
                                            'href' => route('students.delete', $routeParamsWithId)
                                        ])
                                    @endcan
                                @endif

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
