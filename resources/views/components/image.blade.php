{{--
    $image -> string | url
    $imgAttr -> (optional) string
    $originalSrc -> (optional) boolean, whether to include 'original-src' attribute
    $classes -> (optional) string
    $default -> string (icon | image)
    $defaultSrc -> string (asset url)
    $defaultIcon -> (optional) string
    $iconClasses -> (optional) string
    $urlWrapper -> string (link image to resource)
    $urlTooltip -> string
--}}

@isset($urlWrapper)
    <a href="{{ $urlWrapper }}" target="_blank"
        @isset($urlTooltip)
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $urlTooltip }}"
        @endisset
        >
@endisset

@if (isset($image) && Storage::disk('public')->exists($image))

    <img class="{{ $classes ?? 'rounded-circle' }}" alt="user-img"
        src="{{ asset(Storage::url($image)) }}" {!! $imgAttr ?? '' !!}
        {!! isset($originalSrc) ? ('original-src="' . asset(Storage::url($image)) .'"') : '' !!} />

@elseif (isset($image) && filter_var($image, FILTER_VALIDATE_URL))

    <img class="{{ $classes ?? 'rounded-circle' }}" alt="user-img"
        src="{{ $image }}" {!! $imgAttr ?? '' !!}
        {!! isset($originalSrc) ? ('original-src="' . $image .'"') : '' !!} />

@else
    @isset($default)

        <img class="{{ $classes ?? 'rounded-circle' }}" alt="default-image"
            src="{{ $defaultSrc }}" {!! $imgAttr ?? '' !!}
            {!! isset($originalSrc) ? ('original-src="' . $defaultSrc .'"') : '' !!} />

    @else
        <span class="material-icons icon-xxx-large {{ $iconClasses ?? '' }}">
            {{ $defaultIcon ?? 'person_outline' }}</span>
    @endisset
@endif

@isset($urlWrapper)
    </a>
@endisset
