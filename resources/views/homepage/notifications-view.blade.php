@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div>
        <div class="dropdown">
            <button class="btn btn-secondary d-inline-flex align-items-center me-2 dropdown-toggle"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="material-icons mx-1">add</span>
                New
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <span class="material-icons">campaign</span>
                    Announcement
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <span class="material-icons">download_done</span>
                    Download
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <span class="material-icons">description</span>
                    Notice
                </a>
                <div role="separator" class="dropdown-divider my-1"></div>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <span class="material-icons">apartment</span>
                    Tender
                </a>
            </div>
        </div>
    </div>
</div>

<div class="my-3">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Homepage Notifications</h1>
            {{-- <p class="mb-0"></p> --}}
        </div>
        <div>
            <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <span class="material-icons mx-1">help</span>
                Useful Btn
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        @if (count($notifications['data']) == 0)
        <p>Nothing to display!</p>
        @else
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0 rounded-start">#</th>
                        <th class="border-0">Type</th>
                        <th class="border-0">Display Text</th>
                        <th class="border-0">Link</th>
                        <th class="border-0">Created At</th>
                        <th class="border-0">Updated At</th>
                        <th class="border-0">Edit</th>
                        <th class="border-0 rounded-end">Remove</th>
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
                            {{ $notice['display_text'] }}
                        </td>
                        <td>
                            <a class="text-info" href="{{ $notice['link'] }}" target="_blank">{{ $notice['link'] }}</a>
                        </td>
                        <td>
                            {{ date('d-m-Y', strtotime($notice['created_at'])) }}
                        </td>
                        <td>
                            {{ date('d-m-Y', strtotime($notice['updated_at'])) }}
                        </td>
                        <td>
                            <a class="text-primary" href=""><span class="material-icons">edit</span></a>
                        </td>
                        <td>
                            <a class="text-danger" href=""><span class="material-icons">delete</span></a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
