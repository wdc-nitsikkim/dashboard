@extends('layouts.admin', ['title' => 'Manage Site Settings'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Click on an option to execute the command
    @endslot

    @slot('subheading')
        Current app environment: <span class="fw-bolder">{{ strtoupper(App::environment()) }}</span>
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <h5 class="mb-3">Available options</h5>

        <div class="mb-3">

            @component('components.inline.anchorBtn', [
                'href' => route('artisan.command', 'cache:clear'),
                'classes' => 'btn-lg btn-outline-tertiary mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Clear Cache
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => route('artisan.command', 'config:clear'),
                'classes' => 'btn-lg btn-outline-tertiary mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Clear Config Cache
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => route('artisan.command', 'route:clear'),
                'classes' => 'btn-lg btn-outline-tertiary mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Clear Route Cache
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => route('artisan.command', 'view:clear'),
                'classes' => 'btn-lg btn-outline-tertiary mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Clear View Cache
            @endcomponent

        </div>

        <div class="mb-3">

            @component('components.inline.anchorBtn', [
                'href' => route('artisan.command', 'config:cache'),
                'classes' => 'btn-lg btn-outline-tertiary mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Rebuild Config Cache
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => route('artisan.command', 'route:cache'),
                'classes' => 'btn-lg btn-outline-tertiary mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Cache Routes
            @endcomponent

        </div>

        <div class="mb-3">

            @component('components.inline.anchorBtn', [
                'href' => route('artisan.command', 'storage:link'),
                'classes' => 'btn-lg btn-outline-tertiary mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Link Storage
            @endcomponent

        </div>
    </div>
</div>

@endsection
