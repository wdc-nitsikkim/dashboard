@extends('layouts.admin', ['title' => 'Add New ' . $type])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Homepage - Add Notification
    @endslot

    @slot('sideButtons')
        <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
            <span class="material-icons mx-1">help</span>
        </a>
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('admin.homepage.notification.saveNew') }}"
            enctype="multipart/form-data" method="POST">
            {{ csrf_field() }}

            @php
                $type = old('type') ?? $type;
            @endphp

            <div class="row g-2 mb-3">
                <div class="col-sm-12 mb-2">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('display_text') ? 'is-invalid' : '' }}"
                            id="display_text" placeholder="Text to display"
                            name="display_text" value="{{ old('display_text') }}" required>
                        <label for="display_text">Display Text</label>

                        @if ($errors->has('display_text'))
                            <div class="invalid-feedback">
                                {{ $errors->first('display_text') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <select class="form-select {{ $errors->has('type') ? 'is-invalid' : '' }}"
                            id="type" name="type" required>
                            <option value="announcement" {{ $type == 'announcement' ? 'selected' : '' }}>Announcement</option>
                            <option value="download" {{ $type == 'download' ? 'selected' : '' }}>Download</option>
                            <option value="notice" {{ $type == 'notice' ? 'selected' : '' }}>Notice</option>
                            <option value="tender" {{ $type == 'tender' ? 'selected' : '' }}>Tender</option>
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
                            id="link" placeholder="Redirect Link" name="link" value="{{ old('link') }}">
                        <label for="link">Redirect Link</label>

                        @if ($errors->has('link'))
                            <div class="invalid-feedback">
                                {{ $errors->first('link') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="row mb-3">
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

            @component('components.form.footerAdd')
                @slot('returnRoute')
                    {{ route('admin.homepage.notification.show') }}
                @endslot

                @slot('submitBtnTxt')
                    Add Notification
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection
