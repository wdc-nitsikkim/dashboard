{{--
    $notifications -> paginated (in array) collection of HomepageNotification model
    $pagination -> pagination links view
    $canUpdate -> boolean
    $canDelete -> boolean
--}}

@extends('layouts.admin', ['title' => 'View Homepage Notifications'])

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
                        href="{{ route('admin.homepage.notification.add', 'announcement') }}">
                        <span class="material-icons">campaign</span>
                        Announcement
                    </a>
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('admin.homepage.notification.add', 'download') }}">
                        <span class="material-icons">download_done</span>
                        Download
                    </a>
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('admin.homepage.notification.add', 'notice') }}">
                        <span class="material-icons">description</span>
                        Notice
                    </a>
                    <div role="separator" class="dropdown-divider my-1"></div>
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('admin.homepage.notification.add', 'tender') }}">
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
        @include('partials.pageSideBtns', [
            'searchRedirect' => route('admin.homepage.notification.searchForm'),
            'backRedirect' => route('admin.homepage.notification.show'),
            'trashRedirect' => route('admin.homepage.notification.show', 'trashed')
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if (count($notifications['data']) == 0)
            <h5 class="text-center text-danger">No results found!</h5>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('admin.homepage.notification.show')
                ])
                @endcomponent
            </p>
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
                                {{ $notice['created_at'] }}
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

                                <a href="{{ route('admin.homepage.notification.changeStatus', ['id' => $notice['id'], 'status' => $query_param]) }}"
                                    class="btn btn-xs {{ $btn_class }} {{ $canUpdate ? '' : 'disabled' }}"
                                    spoof spoof-method="POST">
                                    {{ $btn_text }}
                                </a>
                            </td>
                            <td>

                                @if ($notice['deleted_at'] == null)
                                    @if ($canUpdate)
                                        @include('components.table.actionBtn.edit', [
                                            'href' => route('admin.homepage.notification.edit', $notice['id'])
                                        ])
                                        @include('components.table.actionBtn.trash', [
                                            'href' => route('admin.homepage.notification.softDelete', $notice['id'])
                                        ])
                                    @endif
                                @else
                                    @if ($canUpdate)
                                        @include('components.table.actionBtn.restore', [
                                            'href' => route('admin.homepage.notification.restore', $notice['id'])
                                        ])
                                    @endif

                                    @if ($canDelete)
                                        @include('components.table.actionBtn.delete', [
                                            'href' => route('admin.homepage.notification.delete', $notice['id'])
                                        ])
                                    @endif
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
