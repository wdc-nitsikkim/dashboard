{{-- 'mx-1' -> 'ms-1' or 'me-1' --}}
<a class="{{ $classes ?? 'text-primary' }} {{ $align ?? 'mx-1' }}"
    {!! $tooltip ? 'data-bs-toggle="tooltip"' : '' !!} title="{{ $tooltip ?? 'Action' }}"
    href="{{ $route ?? '#!' }}" {!! $attr ?? '' !!}>

    @isset($icon)
        <span class="material-icons {{ $scale ? 'scale-on-hover' : '' }}">{{ $icon }}</span>
    @endisset
    {{ $slot ?? '' }}
</a>
