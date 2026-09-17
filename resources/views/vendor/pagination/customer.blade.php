@if ($paginator->hasPages())
    <nav class="pagination-nav" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <p class="pagination-summary">
            @if ($paginator->firstItem())
                Showing <strong>{{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong> creations
            @else
                Showing <strong>0</strong> creations
            @endif
        </p>

        <div class="pagination-controls">
            @if ($paginator->onFirstPage())
                <span class="pagination-arrow is-disabled" aria-disabled="true">&larr; <span>Previous</span></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-arrow" aria-label="{{ __('pagination.previous') }}">&larr; <span>Previous</span></a>
            @endif

            <div class="pagination-pages">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="pagination-ellipsis" aria-hidden="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-page is-current" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-page" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-arrow" aria-label="{{ __('pagination.next') }}"><span>Next</span> &rarr;</a>
            @else
                <span class="pagination-arrow is-disabled" aria-disabled="true"><span>Next</span> &rarr;</span>
            @endif
        </div>
    </nav>
@endif
