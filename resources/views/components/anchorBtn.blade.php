@php
    $tooltipAttr = 'data-bs-toggle=tooltip';
@endphp

<a href="{{ $href ?? '#!' }}"
    class="btn {{ $classes ?? 'btn-outline-primary' }} d-inline-flex
    align-items-center ms-1" {{ $attr ?? '' }}
    {{ isset($tooltip) ? $tooltipAttr : '' }}>
    @isset($icon)
        <span class="material-icons ms-1">{{ $icon }}</span>
    @endisset

    {{ $slot }}
</a>
