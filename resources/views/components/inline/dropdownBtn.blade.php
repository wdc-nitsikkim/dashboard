{{--
    $btnText -> string
    $dropdownIcon -> (optional) string
    $routeName => string
    $all -> (optional) all btn route url
    $iterator -> iterator (any) Eg.: array, collection, ...
    $routeParam -> string
    $routeKey -> string
    $displayKey -> string
    $route -> string
    $baseParams -> (optional) array
--}}

<button class="btn {{ $classes ?? 'btn-gray-800' }} d-inline-flex align-items-center dropdown-toggle mb-2"
    data-bs-toggle="dropdown">
    {{ $btnText }}
    <span class="material-icons ms-1">{{ $dropdownIcon ?? 'keyboard_arrow_down' }}</span>
</button>
<div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2">
    @isset($all)
        <a class="dropdown-item d-flex align-items-center"
            href="{{ $all }}">
            All
        </a>
    @endisset

    @foreach ($iterator as $item)
        <a class="dropdown-item d-flex align-items-center"
            href="{{ route($route, array_merge($baseParams ?? [], [
                $routeParam => $item[$routeKey]
            ])) }}">
            {{ $item[$displayKey] }}</a>
    @endforeach

</div>
