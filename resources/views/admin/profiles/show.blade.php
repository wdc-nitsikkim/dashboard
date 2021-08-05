@extends('layouts.admin', ['title' => 'Profiles'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Registered Profiles
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2 mb-3">

    @foreach ($profiles['data'] as $profile)
        <div class="col">
            @include('components.card', [
                'name' => $profile['name'],
                'designation' => $profile['designation'],
                'image' => $profile['image'],
                'email' => $profile['email'],
                'mobile' => $profile['mobile'],
                'department' => $departmentMap[$profile['department_id']]
            ])
        </div>
    @endforeach

</div>

<nav class="my-3 d-flex justify-content-between">
    {{ $pagination }}
</nav>

@endsection
