{{--
    $href -> route url
--}}

@component('components.inline.anchorLink', [
        'route' => $href,
        'classes' => 'text-danger',
        'icon' => 'delete',
        'tooltip' => 'Delete',
        'scale' => true
    ])

    @slot('attr')
        confirm alert-title="Move to Trash?" alert-text="-""
        alert-timer="3600" spoof spoof-method="DELETE"
    @endslot
@endcomponent
