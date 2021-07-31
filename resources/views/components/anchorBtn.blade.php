@php
    $tooltipAttr = 'data-bs-toggle=tooltip';
@endphp

<a href="{{ $href ?? '#!' }}"
    class="btn {{ $classes ?? 'btn-outline-primary' }} d-inline-flex
    align-items-center ms-1" {{ $attr ?? '' }}
    {{ isset($tooltip) ? $tooltipAttr : '' }}>
    <span class="material-icons mx-1">{{ $icon ?? '' }}</span>
    {{ $slot }}
</a>
