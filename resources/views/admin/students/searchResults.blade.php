{{--
    $students -> collection of student model
    $pagination -> pagination links view
--}}

@extends('layouts.admin', ['title' => 'Search Students'])

@section('content')

@php
    $studentModel = 'App\\Models\\Student';
@endphp

@component('components.page.heading')
    @slot('heading')
        Student List - Search Results
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
            'backRedirect' => route('admin.students.searchForm')
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if (count($students['data']) == 0)
            <h5 class="text-center text-danger">No results found!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('admin.students.searchForm')
                ])
                @endcomponent
            </p>
        @else
            @component('components.table.main')
                @slot('head')
                    @component('components.table.head', [
                        'items' => [
                            '#', 'Roll Number', 'Name',
                            'Department', 'Batch', 'Actions'
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
                                <a class="text-info" href="{{ route('student.home', $student['roll_number']) }}">
                                    <span class="d-inline-block text-truncate" style="max-width: 200px"
                                        data-bs-toggle="tooltip"
                                        title="{{ ucwords($student['name']) }}">
                                        {{ ucwords($student['name']) }}</span>
                                </a>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px"
                                    data-bs-toggle="tooltip"
                                    title="{{ $student['department']['name'] }}">
                                    {{ $student['department']['name'] }}</span>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 150px">
                                    {{ $student['batch']['course']['name'] . ', ' .
                                    $student['batch']['start_year'] }}
                                </span>
                            </td>

                            <td>

                                @php
                                    $routeParams = [
                                        'dept' => $student['department']['code'],
                                        'batch' => $student['batch']['code'],
                                        'id' => $student['id']
                                    ];
                                @endphp

                                @if ($student['deleted_at'] == null)
                                    @can('update', [$studentModel, $student])
                                        @include('components.table.actionBtn.edit', [
                                            'href' => route('admin.students.edit', $routeParams)
                                        ])
                                        @include('components.table.actionBtn.trash', [
                                            'href' => route('admin.students.softDelete', $routeParams)
                                        ])
                                    @endcan
                                @else
                                    @can('update', [$studentModel, $student])
                                        @include('components.table.actionBtn.restore', [
                                            'href' => route('admin.students.restore', $routeParams)
                                        ])
                                    @endcan

                                    @can('delete', [$studentModel, $student])
                                        @include('components.table.actionBtn.delete', [
                                            'href' => route('admin.students.delete', $routeParams)
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
