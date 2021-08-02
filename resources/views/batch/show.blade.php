@extends('layouts.admin')

@section('content')

@php
    $batchModel = 'App\\Models\\Batch';
@endphp

@can('create', $batchModel)
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
                        href="{{ route('batch.add') }}">
                        <span class="material-icons">format_list_numbered</span>
                        Batch
                    </a>
                </div>
            </div>
        </div>
    </div>
@endcan

@component('components.page.heading')
    @slot('heading')
        Batch List
        @slot('subheading')
            It is recommended to leave these values in their default states
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
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="mb-2">
                    <h4>B.Tech</h4>
                </div>

                @if (count($btechBatches['data']) == 0)
                    <h5 class="text-center text-danger">No results found!</h5>
                @else
                    @component('components.table.main')
                        @slot('head')
                            @component('components.table.head', [
                                    'items' => [
                                        '#', 'Batch', 'Name', 'Start Year',
                                        'Actions'
                                    ]
                                ])
                            @endcomponent
                        @endslot

                        @slot('body')
                            @foreach ($btechBatches['data'] as $batch)
                                <tr>
                                    <td>
                                        <span class="text-primary fw-bold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        {{ $batch['batch'] }}
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px" data-bs-toggle="tooltip"
                                            title="{{ $batch['full_name'] }}">
                                            {{ $batch['full_name'] }}</span>
                                    </td>
                                    <td>
                                        {{ $batch['start_year'] }}
                                    </td>
                                    <td>

                                        @can('update', $batchModel)
                                            @include('components.table.actionBtn.edit', [
                                                'href' => route('batch.edit', $batch['id'])
                                            ])
                                            @include('components.table.actionBtn.delete', [
                                                'href' => route('batch.delete', $batch['id'])
                                            ])
                                        @endcan

                                    </td>
                                </tr>
                            @endforeach
                        @endslot
                    @endcomponent

                    <nav class="my-3 d-flex justify-content-between">
                        {{ $btechPagination }}
                    </nav>
                @endif

            </div>

            <div class="col-md-6 col-sm-12">
                <div class="mb-2">
                    <h4>M.Tech</h4>
                </div>

                @if (count($mtechBatches['data']) == 0)
                    <h5 class="text-center text-danger">No results found!</h5>
                @else
                    @component('components.table.main')
                        @slot('head')
                            @component('components.table.head', [
                                    'items' => [
                                        '#', 'Batch', 'Name', 'Start Year',
                                        'Actions'
                                    ]
                                ])
                            @endcomponent
                        @endslot

                        @slot('body')
                            @foreach ($mtechBatches['data'] as $batch)
                                <tr>
                                    <td>
                                        <span class="text-primary fw-bold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        {{ $batch['batch'] }}
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px" data-bs-toggle="tooltip"
                                            title="{{ $batch['full_name'] }}">
                                            {{ $batch['full_name'] }}</span>
                                    </td>
                                    <td>
                                        {{ $batch['start_year'] }}
                                    </td>
                                    <td>

                                        @can('update', $batchModel)
                                            @include('components.table.actionBtn.edit', [
                                                'href' => route('batch.edit', $batch['id'])
                                            ])
                                            @include('components.table.actionBtn.delete', [
                                                'href' => route('batch.delete', $batch['id'])
                                            ])
                                        @endcan

                                    </td>
                                </tr>
                            @endforeach
                        @endslot
                    @endcomponent

                    <nav class="my-3 d-flex justify-content-between">
                        {{ $mtechPagination }}
                    </nav>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
