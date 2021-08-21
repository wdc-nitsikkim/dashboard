@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => $titleUrl ?? config('app.url')])
            {{ $title ?? config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            &copy; {{ date('Y') }} Web Development Cell, NIT Sikkim
        @endcomponent
    @endslot
@endcomponent
