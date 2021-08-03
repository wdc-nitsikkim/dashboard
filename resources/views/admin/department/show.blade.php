@extends('layouts.admin', ['title' => 'View Department List'])

@section('content')

@php
    $deptModel = 'App\\Models\\Department';
@endphp

@can('create', $deptModel)
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
                        href="{{ route('admin.department.add') }}">
                        <span class="material-icons">business</span>
                        Department
                    </a>
                </div>
            </div>
        </div>
    </div>
@endcan

@component('components.page.heading')
    @slot('heading')
        Department List
        @slot('subheading')
            It is recommended to leave these values in their default states<br>
            @can('delete', $deptModel)
                <span class="text-danger fw-bolder">WARNING: Removing a department permanently will remove
                    all data associated with it (students, faculty, ...)! Proceed with extreme caution.</span>
            @endcan
        @endslot
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if (count($departments['data']) == 0)
            <h5 class="text-center text-danger">No results found!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                        'href' => route('admin.department.show')
                    ])
                @endcomponent
            </p>
        @else
            @component('components.table.main')
                @slot('head')
                    @component('components.table.head', [
                            'items' => [
                                '#', 'Code', 'Name', 'Created At', 'Actions'
                            ]
                        ])
                    @endcomponent
                @endslot

                @slot('body')
                    @foreach ($departments['data'] as $department)
                        <tr class="{{ $department['deleted_at'] != null ? 'text-danger' : ''}}">
                            <td>
                                <span class="text-primary fw-bold">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                {{ $department['code'] }}
                            </td>
                            <td>
                                <span class="fw-bolder">
                                    {{ $department['name'] }}</span>
                            </td>
                            <td>
                                {{ date('d-m-Y', strtotime($department['created_at'])) }}
                            </td>
                            <td>

                                @if ($department['deleted_at'] == null)
                                    @can('update', $deptModel)
                                        @include('components.table.actionBtn.edit', [
                                            'href' => route('admin.department.edit', $department['id'])
                                        ])
                                        @include('components.table.actionBtn.trash', [
                                            'href' => route('admin.department.softDelete', $department['id'])
                                        ])
                                    @endcan
                                @else
                                    @can('update', $deptModel)
                                        @include('components.table.actionBtn.restore', [
                                            'href' => route('admin.department.restore', $department['id'])
                                        ])
                                    @endcan

                                    @can('delete', $deptModel)
                                        @include('components.table.actionBtn.delete', [
                                            'href' => route('admin.department.delete', $department['id'])
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
