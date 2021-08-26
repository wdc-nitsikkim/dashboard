@extends('layouts.admin', ['title' => 'Student Profile'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Looks like you are not registered as a student...
    @endslot

    @slot('subheading')
        <h5> Contact your <span class="fw-bolder">Department head</span>
            for more information</h5>
        <p class="text-danger fw-bolder">You need to be present in the list of resgistered
            students to view this page</p>
        <p>If you are registered & still seeing this page, double check
            your email address you provided
            <a class="text-info fw-bolder" href="{{ route('users.account', Auth::id()) }}"
                data-bs-toggle="tooltip" title="View account">
                (account email id <span class="material-icons fs-5">open_in_new</span>).
            </a>
            It should be the same as the email address provided by the college.
            <br>
            <span class="fw-bolder">All students are required to have their college email
            id as their primary (account) email id.</span></p>
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
        ])
    @endslot
@endcomponent

@endsection
