{{--
    $preferred -> collection of subject model
    $subjects -> collection of subject model
--}}

@extends('layouts.admin', ['title' => 'Select Subject'])

@section('content')

@php
    $redirectHandler = route('admin.subjects.select');
@endphp

@component('components.page.heading')
    @slot('heading')
        Select a subject to proceed
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
            'batchRedirect' => $redirectHandler,
            'deptRedirect' => $redirectHandler
        ])
    @endslot
@endcomponent

@php
    $redirect = \request()->query('redirect') ?? '';
@endphp

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <h5>Additional Access</h5>
        <div class="mb-3">

            @if ($preferred->count() > 0)
                @foreach ($preferred as $subject)
                    @component('components.inline.anchorBtn', [
                        'href' => route('admin.subjects.saveInSession', [
                            'subject' => $subject->registeredSubject->subject_code,
                            'redirect' => $redirect
                        ]),
                        'classes' => 'btn-lg btn-outline-tertiary mb-2',
                        'tooltip' => true
                    ])
                        @slot('attr')
                            spoof spoof-method="POST"
                            data-bs-placement="top" title="{{ $subject->registeredSubject->subject->name }}"
                        @endslot
                        {{ $subject->registeredSubject->subject_code }}
                    @endcomponent
                @endforeach
            @else
                <p class="text-danger">No Results / Not Applicable</p>
            @endif

        <hr/>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="me-3">All Subjects</h5>
            </div>
            <div>
                <div class="input-group">
                    <span class="input-group-text">
                        <span class="material-icons">search</span>
                    </span>

                    <input type="text" class="form-control"
                        placeholder="Find" find
                        find-in="#all-subjects a" hide-tag="a"
                        additional-attr="name" loader="#find-subject-loader"
                        status="#find-subjects-status" autofocus>

                    <span id="find-subject-loader" class="input-group-text d-none">
                        <div class="text-danger spinner-border spinner-border-sm"
                            role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </span>
                </div>
            </div>
        </div>

        <p class="small" id="find-subjects-status">-</p>

        <div class="mb-3" id="all-subjects">

            @if ($subjects->count() > 0)
                @foreach ($subjects as $subject)
                    @component('components.inline.anchorBtn', [
                        'href' => route('admin.subjects.saveInSession', [
                            'subject' => $subject->id,
                            'redirect' => $redirect
                        ]),
                        'classes' => 'btn-lg btn-outline-tertiary mb-2',
                        'tooltip' => true
                    ])
                        @slot('attr')
                            name="{{ $subject->subject->name }}"
                            spoof spoof-method="POST"
                            data-bs-placement="top" title="{{ $subject->subject->name }}"
                        @endslot
                        {{ $subject->subject_code }}
                    @endcomponent
                @endforeach
            @else
                <p class="text-danger">No Results / Not Applicable</p>
            @endif

        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('static/js/find.js') }}"></script>
@endpush
