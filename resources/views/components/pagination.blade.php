@if ($paginator->hasPages())
<nav class="pagination" aria-label="Navegación de páginas">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="page-btn page-btn-disabled" aria-disabled="true">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" rel="prev">‹</a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-btn page-btn-dots">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-btn page-btn-active" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next">›</a>
    @else
        <span class="page-btn page-btn-disabled" aria-disabled="true">›</span>
    @endif
</nav>
@endif
