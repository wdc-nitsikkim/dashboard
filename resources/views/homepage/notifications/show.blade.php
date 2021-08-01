@extends('layouts.admin')

@section('content')

@php
    $notiModel = 'App\\Models\\HomepageNotification';
@endphp

@can('create', $notiModel)
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
                        href="{{ route('homepage.notification.add', 'announcement') }}">
                        <span class="material-icons">campaign</span>
                        Announcement
                    </a>
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('homepage.notification.add', 'download') }}">
                        <span class="material-icons">download_done</span>
                        Download
                    </a>
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('homepage.notification.add', 'notice') }}">
                        <span class="material-icons">description</span>
                        Notice
                    </a>
                    <div role="separator" class="dropdown-divider my-1"></div>
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('homepage.notification.add', 'tender') }}">
                        <span class="material-icons">apartment</span>
                        Tender
                    </a>
                </div>
            </div>
        </div>
    </div>
@endcan

@component('components.pageHeading')
    @slot('heading')
        Homepage - Notifications
    @endslot

    @slot('sideButtons')
        @if (Route::is('homepage.notification.show'))
            @component('components.anchorBtn', [
                    'href' => route('homepage.notification.showTrashed'),
                    'classes' => 'btn-outline-info',
                    'tooltip' => true,
                    'icon' => 'restore_from_trash'
                ])

                @slot('attr')
                    data-bs-placement="left" title="Trashed"
                @endslot
                Trashed
            @endcomponent
        @else
            @component('components.anchorBtn', [
                    'href' => route('homepage.notification.show'),
                    'classes' => 'btn-outline-info',
                    'icon' => 'keyboard_arrow_left'
                ])

                Back
            @endcomponent
        @endif
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if (count($notifications['data']) == 0)
            <h5 class="text-center text-danger">No results found!</h5>
        @else
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0 rounded">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0 col rounded-start">#</th>
                            <th class="border-0 col">Type</th>
                            <th class="border-0 col">Display Text</th>
                            <th class="border-0 col">Link</th>
                            <th class="border-0 col">Created At</th>
                            {{-- <th class="border-0 col">Updated At</th> --}}
                            <th class="border-0 col">Status</th>
                            <th class="border-0 col rounded-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($notifications['data'] as $notice)
                            <tr>
                                <td>
                                    <span class="text-primary fw-bold">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    {{ ucfirst($notice['type']) }}
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 200px" data-bs-toggle="tooltip"
                                        title="{{ $notice['display_text'] }}">
                                        {{ $notice['display_text'] }}</span>
                                </td>
                                <td>
                                    <a class="d-inline-block text-truncate text-info" style="max-width: 150px"
                                        href="{{ $notice['link'] }}" target="_blank" data-bs-toggle="tooltip"
                                        title="{{ $notice['link'] }}">
                                        {{ $notice['link'] }}</a>
                                </td>
                                <td>
                                    {{ date('d-m-Y', strtotime($notice['created_at'])) }}
                                </td>
                                {{-- <td>
                                    {{ date('d-m-Y', strtotime($notice['updated_at'])) }}
                                </td> --}}
                                <td>
                                    @php
                                        $btn_class = 'btn-success';
                                        $btn_text = 'Active';
                                        $query_param = 'disable';
                                        if ($notice['status'] == 0) {
                                            $btn_class = 'btn-danger';
                                            $btn_text = 'Hidden';
                                            $query_param = 'enable';
                                        }
                                    @endphp

                                    <a href="{{ route('homepage.notification.changeStatus', ['id' => $notice['id'], 'status' => $query_param]) }}"
                                        class="btn btn-xs {{ $btn_class }}" spoof spoof-method="POST">
                                        {{ $btn_text }}
                                    </a>
                                </td>
                                <td>

                                    @if ($notice['deleted_at'] == null)
                                        @can('update', $notiModel)
                                            <a class="text-primary mx-1" data-bs-toggle="tooltip" title="Edit"
                                                href="{{ route('homepage.notification.edit', $notice['id']) }}">
                                                <span class="material-icons scale-on-hover">edit</span></a>
                                            <a class="text-danger mx-1" data-bs-toggle="tooltip" title="Delete"
                                                href="{{ route('homepage.notification.softDelete', $notice['id']) }}"
                                                alert-title="Move to Trash?" alert-text="-"
                                                confirm spoof spoof-method="DELETE">
                                                <span class="material-icons scale-on-hover">delete</span></a>
                                        @endcan
                                    @else
                                        @can('update', $notiModel)
                                            <a class="text-success mx-1" data-bs-toggle="tooltip" title="Restore"
                                                href="{{ route('homepage.notification.restore', $notice['id']) }}"
                                                spoof spoof-method="POST">
                                                <span class="material-icons scale-on-hover">restore</span></a>
                                        @endcan

                                        @can('delete', $notiModel)
                                            <a class="text-danger mx-1" data-bs-toggle="tooltip" title="Delete Permanently"
                                                href="{{ route('homepage.notification.delete', $notice['id']) }}"
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

            <nav class="my-3">
                {{ $pagination }}
            </nav>
        @endif

    </div>
</div>

@endsection
