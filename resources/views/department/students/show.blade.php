@extends('layouts.admin')

@section('content')

@php
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

                    @if (Auth::user()->can('create', [\App\Models\Student::class, $department]))
                        <a class="dropdown-item d-flex align-items-center"
                            href="{{ route('department.students.add', $baseRouteParams) }}">
                            <span class="material-icons">face</span>
                            Student
                        </a>
                    @endif

                    @if (Auth::user()->can('create', \App\Models\Batch::class))
                        <a class="dropdown-item d-flex align-items-center"
                            href="#!">
                            <span class="material-icons">format_list_numbered</span>
                            Batch
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endif

<div class="my-3">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Student List - {{ $batch['full_name'] }}</h1>
            <p class="mb-0">
                @if ($batch['type'] == 'b')
                    B.Tech ({{ $batch['start_year'] . ' - ' . ($batch['start_year'] + 4)  }})
                @else
                    M.Tech ({{ $batch['start_year'] . ' - ' . ($batch['start_year'] + 2) }})
                @endif
                ,
                {{ $department['name'] }}
            </p>
        </div>
        <div>
            <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <span class="material-icons mx-1">help</span>
            </a>

            @component('components.anchorBtn', [
                    'icon' => 'import_export',
                    'href' => route('department.select'),
                    'classes' => 'btn-outline-info',
                    'tooltip' => true
                ])
                @slot('attr')
                    data-bs-placement="left" title="Change Department"
                @endslot
                Department
            @endcomponent

            @component('components.anchorBtn', [
                    'icon' => 'import_export',
                    'href' => route('department.students.selectBatch', $department),
                    'classes' => 'btn-outline-info',
                    'tooltip' => true
                ])
                @slot('attr')
                    data-bs-placement="left" title="Change Batch"
                @endslot
                Batch
            @endcomponent

        </div>
    </div>
</div>

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
                                        @if (Auth::user()->can('update', [\App\Models\Student::class, $department]))
                                            <a class="text-primary mx-1" data-bs-toggle="tooltip" title="Edit"
                                                href="{{ route('department.students.edit', $routeParamsWithId) }}">
                                                <span class="material-icons">edit</span></a>
                                            <a class="text-danger mx-1" data-bs-toggle="tooltip" title="Delete"
                                                href="{{ route('department.students.softDelete', $routeParamsWithId) }}"
                                                alert-title="Move to Trash?" alert-text="-"
                                                confirm spoof spoof-method="DELETE"><span class="material-icons">delete</span></a>
                                        @endif
                                    @else
                                        @if (Auth::user()->can('update', [\App\Models\Student::class, $department]))
                                            <a class="text-success mx-1" data-bs-toggle="tooltip" title="Restore"
                                                href="{{ route('department.students.restore', $routeParamsWithId) }}"
                                                spoof spoof-method="POST">
                                                <span class="material-icons">restore</span></a>
                                        @endif
                                        @if (Auth::user()->can('delete', [\App\Models\Student::class, $department]))
                                            <a class="text-danger mx-1" data-bs-toggle="tooltip"
                                                title="Delete Permanently"
                                                href="{{ route('department.students.delete', $routeParamsWithId) }}"
                                                alert-title="Delete Permanently?" confirm spoof spoof-method="DELETE">
                                                <span class="material-icons">delete_forever</span></a>
                                        @endif
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
