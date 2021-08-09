{{--
    $href -> route url
--}}

@component('components.inline.anchorLink', [
    'route' => $href,
    'classes' => 'text-danger',
    'icon' => 'delete_forever',
    'tooltip' => 'Delete Permanently',
    'scale' => true
])

    @slot('attr')
        confirm alert-title="Delete Permanently?" alert-text="You won't be able to revert this!"
        alert-timer="3600" spoof spoof-method="DELETE"
    @endslot
@endcomponent
