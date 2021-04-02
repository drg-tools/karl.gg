@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="flex justify-center text-orange">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true">
                    <span class="px-4 py-3 block" aria-hidden="true">
                        @lang('pagination.previous')
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       rel="prev"
                       class="px-4 py-3 block focus:outline-none focus:ring"
                    >
                        @lang('pagination.previous')
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       rel="next"
                       class="px-4 py-3 block focus:outline-none focus:ring"
                    >
                        @lang('pagination.next')
                    </a>
                </li>
            @else
                <li aria-disabled="true">
                    <span class="px-4 py-3 block">
                        @lang('pagination.next')
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif

