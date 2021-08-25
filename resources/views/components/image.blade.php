{{--
    $image -> string | url
    $imgAttr -> (optional) string
    $originalSrc -> (optional) boolean, whether to include 'original-src' attribute
    $classes -> (optional) string
    $default -> string (icon | image)
    $defaultIcon -> (optional) string
--}}

@if (isset($image) && Storage::disk('public')->exists($image))

    <img class="rounded-circle {{ $classes ?? '' }}" alt="user-img"
        src="{{ asset(Storage::url($image)) }}" {!! $imgAttr ?? '' !!}
        {!! isset($originalSrc) ? ('original-src="' . asset(Storage::url($image)) .'"') : '' !!} />

@elseif (isset($image) && filter_var($image, FILTER_VALIDATE_URL))

    <img class="rounded-circle" alt="user-img"
        src="{{ $image }}" {!! $imgAttr ?? '' !!}/>

@else
    @isset($default)

        <img class="rounded-circle {{ $classes ?? '' }}" alt="default-image"
            src="{{ asset('static/images/user-default.png') }}" {!! $imgAttr ?? '' !!}
            {!! isset($originalSrc) ? ('original-src="' . asset('static/images/user-default.png') .'"') : '' !!} />

    @else
        <span class="material-icons icon-xxx-large">{{ $defaultIcon ?? 'person_outline' }}</span>
    @endisset
@endif
