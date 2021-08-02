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

@component('components.page.heading')
    @slot('heading')
        Homepage - Notifications
    @endslot

    @slot('sideButtons')
        @if (Route::is('homepage.notification.show'))
            @include('partials.pageSideBtns', [
                'trashRedirect' => route('homepage.notification.showTrashed')
            ])
        @else
            @include('partials.pageSideBtns', [
                'backRedirect' => route('homepage.notification.show')
            ])
        @endif
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if (count($notifications['data']) == 0)
            <h5 class="text-center text-danger">No results found!</h5>
        @else
            @component('components.table.main')
                @slot('head')
                    @component('components.table.head', [
                            'items' => [
                                '#', 'Type', 'Display Text', 'Link',
                                'Created At', 'Status', 'Actions'
                            ]
                        ])
                    @endcomponent
                @endslot

                @slot('body')
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
                                        @include('components.table.actionBtn.edit', [
                                            'href' => route('homepage.notification.edit', $notice['id'])
                                        ])
                                        @include('components.table.actionBtn.trash', [
                                            'href' => route('homepage.notification.softDelete', $notice['id'])
                                        ])
                                    @endcan
                                @else
                                    @can('update', $notiModel)
                                        @include('components.table.actionBtn.restore', [
                                            'href' => route('homepage.notification.restore', $notice['id'])
                                        ])
                                    @endcan

                                    @can('delete', $notiModel)
                                        @include('components.table.actionBtn.delete', [
                                            'href' => route('homepage.notification.delete', $notice['id'])
                                        ])
                                    @endcan
                                @endif

                            </td>
                        </tr>
                    @endforeach
                @endslot
            @endcomponent

            <nav class="my-3">
                {{ $pagination }}
            </nav>
        @endif

    </div>
</div>

@endsection
