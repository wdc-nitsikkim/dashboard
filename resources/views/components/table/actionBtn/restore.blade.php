{{--
    $href -> route url
--}}

@component('components.inline.anchorLink', [
    'route' => $href,
    'classes' => 'text-success',
    'icon' => 'restore',
    'tooltip' => 'Restore',
    'scale' => true
])

    @slot('attr')
        spoof spoof-method="POST"
    @endslot
@endcomponent
