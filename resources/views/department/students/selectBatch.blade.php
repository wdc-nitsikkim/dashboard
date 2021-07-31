@extends('layouts.admin')

@section('content')

@php
    $batchModel = 'App\\Models\\Batch';
@endphp

@can (['create', 'update'], $batchModel)
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mt-3">
        <div>
            <div class="dropdown">
                <button class="btn btn-secondary d-inline-flex align-items-center me-2 dropdown-toggle"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="material-icons mx-1">add</span>
                    New
                </button>
                <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">

                    @can('create', $batchModel)
                        <a class="dropdown-item d-flex align-items-center"
                            href="">
                            <span class="material-icons">add_circle</span>
                            Add New Batch
                        </a>
                    @endcan

                    @can('update', $batchModel)
                        <a class="dropdown-item d-flex align-items-center"
                            href="">
                            <span class="material-icons">edit</span>
                            Edit Existing
                        </a>
                    @endcan

                </div>
            </div>
        </div>
    </div>
@endcan

@component('components.pageHeading')
    @slot('heading')
        Select a batch to proceed
    @endslot

    @slot('subheading')
        {{ $department['name'] }}
    @endslot

    @slot('sideButtons')
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
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 mb-3">
                <h5>B.Tech Batchlist</h5>

                @if ($btechBatches->count() > 0)
                    @foreach ($btechBatches as $batch)
                        @component('components.anchorBtn', [
                                'href' => route('department.students.show', [
                                        'code' => $department,
                                        'batch' => $batch['batch']
                                    ]),
                                'classes' => 'btn-lg btn-outline-tertiary mb-2'
                            ])
                            {{ $batch['start_year'] . ' - ' . ($batch['start_year'] + 4) }}
                        @endcomponent
                    @endforeach
                @else
                    <p class="text-danger">No Results</p>
                @endif

            </div>

            <div class="col-sm-12 mb-3">
                <h5>M.Tech Batchlist</h5>

                @if ($mtechBatches->count() > 0)
                    @foreach ($mtechBatches as $batch)
                        @component('components.anchorBtn', [
                                'href' => route('department.students.show', [
                                        'code' => $department,
                                        'batch' => $batch['batch']
                                    ]),
                                'classes' => 'btn-lg btn-outline-tertiary mb-2'
                            ])
                            {{ $batch['start_year'] . ' - ' . ($batch['start_year'] + 2) }}
                        @endcomponent
                    @endforeach
                @else
                    <p class="text-danger">No Results</p>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
