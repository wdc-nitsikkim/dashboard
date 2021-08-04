{{-- Identical to componenets.inline.anchorLink. Made separate because
    this is specifically for back buttons (pagination -> no results found page) --}}

<a class="{{ $classes ?? 'text-info' }}" href="{{ $href ?? '#!' }}">
    <span class="material-icons">{{ $icon ?? 'keyboard_arrow_left'}}</span>
    @isset($slot)
        {{ $slot == '' ? 'Go Back' : $slot }}
    @endisset
</a>
