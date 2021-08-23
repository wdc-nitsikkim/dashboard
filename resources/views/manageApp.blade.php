@extends('layouts.admin', ['title' => 'Manage App Settings'])

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
                'href' => route('myApp.artisan.command', 'cache:clear'),
                'classes' => 'btn-lg btn-outline-info mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Clear Cache
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => route('myApp.artisan.command', 'config:clear'),
                'classes' => 'btn-lg btn-outline-danger mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Clear Config Cache
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => route('myApp.artisan.command', 'route:clear'),
                'classes' => 'btn-lg btn-outline-tertiary mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST"
                @endslot
                Clear Route Cache
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => route('myApp.artisan.command', 'view:clear'),
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
                'href' => route('myApp.artisan.command', 'config:cache'),
                'classes' => 'btn-lg btn-outline-danger mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST" confirm
                    alert-title="Rebuild Cache?"
                    alert-text="Old cache will be purged"
                @endslot
                Rebuild Config Cache
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => '#!',
                'classes' => 'btn-lg btn-outline-tertiary mb-2 disabled'
            ])
                Cache Routes
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => route('myApp.artisan.command', 'storage:link'),
                'classes' => 'btn-lg btn-outline-tertiary mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST" confirm
                    alert-title="Sure to create a symlink?"
                    alert-text="-"
                @endslot
                Link Storage
            @endcomponent

        </div>

        <h5 class="mb-3">Backup</h5>

        <div class="mb-3">

            @component('components.inline.anchorBtn', [
                'href' => route('myApp.dbBackupCreate'),
                'classes' => 'btn-lg btn-outline-success mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST" confirm
                    alert-title="Create a backup now?"
                    alert-text="It will be downloaded automatically & will be
                    stored in the server filesystem"
                @endslot
                Database Backup
            @endcomponent

            @component('components.inline.anchorBtn', [
                'href' => route('myApp.removeBackupDir'),
                'classes' => 'btn-lg btn-outline-danger mb-2'
            ])
                @slot('attr')
                    spoof spoof-method="POST" confirm
                    alert-title="Destructive Action!" alert-timer="2000"
                @endslot
                Remove all backups
            @endcomponent

        </div>
    </div>
</div>

@endsection
