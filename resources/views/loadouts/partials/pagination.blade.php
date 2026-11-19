@if ($paginator->hasPages())
    <nav class="browse-pagination" aria-label="{{ $label ?? 'Loadout pages' }}">
        @if ($paginator->onFirstPage())
            <span class="browse-pagination__step is-disabled" aria-disabled="true">← Prev</span>
        @else
            <a class="browse-pagination__step" href="{{ $paginator->previousPageUrl() }}" rel="prev" data-browse-link>← Prev</a>
        @endif

        <ol class="browse-pagination__pages">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="browse-pagination__gap" aria-hidden="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="browse-pagination__page is-current" aria-current="page">{{ number_format($page) }}</span>
                            @else
                                <a class="browse-pagination__page" href="{{ $url }}" aria-label="Page {{ $page }}" data-browse-link>{{ number_format($page) }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ol>

        @if ($paginator->hasMorePages())
            <a class="browse-pagination__step" href="{{ $paginator->nextPageUrl() }}" rel="next" data-browse-link>Next →</a>
        @else
            <span class="browse-pagination__step is-disabled" aria-disabled="true">Next →</span>
        @endif
    </nav>
@endif
