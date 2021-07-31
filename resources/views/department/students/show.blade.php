@extends('layouts.admin')

@section('content')

@php
    $batchModel = 'App\\Models\\Batch';
    $studentModel = 'App\\Models\\Student';

    $baseRouteParams = [
        'dept' => $department,
        'batch' => $batch
    ];
@endphp

@if (Auth::user()->can('create', [\App\Models\Student::class, $department])
    || Auth::user()->can('create', \App\Models\Batch::class))

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mt-3">
        <div>
            <div class="dropdown">
                <button class="btn btn-secondary d-inline-flex align-items-center me-2 dropdown-toggle"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="material-icons mx-1">add</span>
                    New
                </button>

                <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">

                    @can('create', [$studentModel, $department])
                        <a class="dropdown-item d-flex align-items-center"
                            href="{{ route('department.students.add', $baseRouteParams) }}">
                            <span class="material-icons">face</span>
                            Student
                        </a>
                    @endcan

                    @can('create', $batchModel)
                        <a class="dropdown-item d-flex align-items-center"
                            href="#!">
                            <span class="material-icons">format_list_numbered</span>
                            Batch
                        </a>
                    @endcan

                </div>
            </div>
        </div>
    </div>
@endif

@component('components.pageHeading')
    @slot('heading')
        Student List - {{ $batch['full_name'] }}
    @endslot

    @slot('subheading')
        @include('department.partials.studentsPageSubheading', ['batch' => $batch])

        {{ $department['name'] }}
    @endslot

    @slot('sideButtons')
        @include('department.partials.studentsPageSwitchBtns', ['department' => $department])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if (count($students['data']) == 0)
            <h5 class="text-center text-danger">No results found!</h5>
        @else
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0 rounded">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0 col rounded-start">#</th>
                            <th class="border-0 col">Roll Number</th>
                            <th class="border-0 col">Name</th>
                            <th class="border-0 col">Email</th>
                            <th class="border-0 col rounded-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

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
                                            <a class="text-primary mx-1" data-bs-toggle="tooltip" title="Edit"
                                                href="{{ route('department.students.edit', $routeParamsWithId) }}">
                                                <span class="material-icons scale-on-hover">edit</span></a>
                                            <a class="text-danger mx-1" data-bs-toggle="tooltip" title="Delete"
                                                href="{{ route('department.students.softDelete', $routeParamsWithId) }}"
                                                alert-title="Move to Trash?" alert-text="-"
                                                confirm spoof spoof-method="DELETE">
                                                <span class="material-icons scale-on-hover">delete</span></a>
                                       @endcan
                                    @else
                                        @can('update', [$studentModel, $department])
                                            <a class="text-success mx-1" data-bs-toggle="tooltip" title="Restore"
                                                href="{{ route('department.students.restore', $routeParamsWithId) }}"
                                                spoof spoof-method="POST">
                                                <span class="material-icons scale-on-hover">restore</span></a>
                                       @endcan

                                       @can('delete', [$studentModel, $department])
                                            <a class="text-danger mx-1" data-bs-toggle="tooltip"
                                                title="Delete Permanently"
                                                href="{{ route('department.students.delete', $routeParamsWithId) }}"
                                                alert-title="Delete Permanently?" confirm spoof spoof-method="DELETE">
                                                <span class="material-icons scale-on-hover">delete_forever</span></a>
                                        @endcan
                                    @endif

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <nav class="my-3 d-flex justify-content-between">
                {{ $pagination }}
            </nav>
        @endif

    </div>
</div>
@endsection
