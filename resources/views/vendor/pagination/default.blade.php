{{--
    $paginator -> paginator instance
--}}

@if ($paginator->hasPages())
    <div class="text-muted">
        Showing {{ $paginator->firstItem() . ' - ' . $paginator->lastItem() }} of
        total {{ $paginator->total() }}
    </div>

    <ul class="pagination {{ isset($pagination_class) ? $pagination_class : '' }}">
        {{-- First Page Link --}}
        {{-- <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}" title="First Page">
            <a class="page-link" href="{{ url()->current() . '?page=1' }}">
                <span class="material-icons">first_page</span>
            </a>
        </li> --}}
        {{-- Previous Page Link --}}
        <li class="page-item {{ $paginator->previousPageUrl() == null ? 'disabled' : '' }}"
            title="Previous Page">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                <span class="material-icons">navigate_before</span>
            </a>
        </li>

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $page == $paginator->currentPage() ? '#!' : $url }}">
                            {{ $page }}</a>
                    </li>
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}" title="Next Page">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                <span class="material-icons">navigate_next</span>
            </a>
        </li>
        {{-- Last Page Link --}}
        {{-- <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}" title="Last Page">
            <a class="page-link" href="{{ url()->current() . '?page=' . $paginator->lastPage() }}">
                <span class="material-icons">last_page</span>
            </a>
        </li> --}}
    </ul>
@endif
