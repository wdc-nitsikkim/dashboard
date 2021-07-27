@extends('layouts.admin')

@section('content')
<div class="my-3">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Homepage - Edit Notification</h1>
            {{-- <p class="mb-0"></p> --}}
        </div>
        <div>
            <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <span class="material-icons mx-1">help</span>
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('homepage.notification.update', $data['id']) }}"
            enctype="multipart/form-data" method="POST">
            {{ csrf_field() }}

            <div class="row g-2 mb-3">
                <div class="col-sm-12">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('display_text') ? 'is-invalid' : '' }}"
                            id="display_text" placeholder="Text to display"
                            name="display_text" value="{{ $data['display_text'] }}" required>
                        <label for="display_text">Display Text</label>

                        @if ($errors->has('display_text'))
                            <div class="invalid-feedback">
                                {{ $errors->first('display_text') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row g-2 mb-1">
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <select class="form-select {{ $errors->has('type') ? 'is-invalid' : '' }}"
                            id="type" name="type" required>
                            <option value="announcement" {{ $data['type'] == 'announcement' ? 'selected' : '' }}>Announcement</option>
                            <option value="download" {{ $data['type'] == 'download' ? 'selected' : '' }}>Download</option>
                            <option value="notice" {{ $data['type'] == 'notice' ? 'selected' : '' }}>Notice</option>
                            <option value="tender" {{ $data['type'] == 'tender' ? 'selected' : '' }}>Tender</option>
                        </select>
                        <label for="type">Notification Type</label>

                        @if ($errors->has('type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('type') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-8 mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}"
                            id="link" placeholder="Redirect Link" name="link" value="{{ $data['link'] }}">
                        <label for="link">Redirect Link</label>

                        @if ($errors->has('link'))
                            <div class="invalid-feedback">
                                {{ $errors->first('link') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-6 col-sm-12 mb-2">
                    <label for="attachment" class="form-label">Add Attachment</label>
                    <input class="form-control {{ $errors->has('attachment') ? 'is-invalid' : '' }}"
                        type="file" id="attachment" name="attachment">
                    <small class="text-muted">Selecting file will automatically overwrite redirect link</small>

                    @if ($errors->has('attachment'))
                        <div class="invalid-feedback">
                            {{ $errors->first('attachment') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="created_at" placeholder="Created At"
                            value="{{ date('d-m-Y, H:i:s', strtotime($data['created_at'])) }}" disabled>
                        <label for="created_at">Created At</label>
                    </div>
                </div>
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="updated_at" placeholder="Updated At"
                            value="{{ date('d-m-Y, H:i:s', strtotime($data['updated_at'])) }}" disabled>
                        <label for="updated_at">Last Updated</label>
                    </div>
                </div>
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <input type="text" class="form-control {{ $data['status'] == '1' ? 'is-valid' : 'is-invalid' }}"
                            id="status" placeholder="Visibility"
                            value="{{ $data['status'] == '1' ? 'Visible' : 'Hidden' }}" disabled>
                        <label for="status">Visibility</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 d-grid gap-1 mx-auto mb-3">
                    <a class="btn btn-primary"
                        href="{{ url()->previous() == url()->current() ? route('homepage.notification.show') : url()->previous() }}">
                        <span class="material-icons mx-1">keyboard_arrow_left</span>
                        Go back
                    </a>
                </div>
                <div class="col-sm-8 d-grid gap-1 mx-auto mb-3">
                    <button class="btn btn-success" type="submit">
                        Update <span class="material-icons mx-1">update</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/homepage-notification.js') }}"></script>
@endpush
