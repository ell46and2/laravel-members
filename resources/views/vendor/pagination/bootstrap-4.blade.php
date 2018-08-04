@if ($paginator->hasPages())
    <nav aria-label="Table Pagination" class="mt-2">
        <ul class="pagination justify-content-center">
              
                @if($paginator->currentPage() > 2)
                    <li class="page-item" aria-current="page">
                        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                    </li>
                @endif
                @if($paginator->currentPage() > 3)
                    <li class="pagination__ellipsis">
                        ...
                    </li>
                @endif
                @foreach(range(1, $paginator->lastPage()) as $i)
                    @if($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 1)
                        @if ($i == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                            </li>
                        @else
                            <li class="page-item" aria-current="page">
                                <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endif
                @endforeach
                @if($paginator->currentPage() < $paginator->lastPage() - 2)
                    <li class="pagination__ellipsis">
                        ...
                    </li>
                @endif
                @if($paginator->currentPage() < $paginator->lastPage() - 1)
                    <li class="page-item" aria-current="page">
                        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                    </li>
                @endif

        </ul>
    </nav>
</div>

<div class="panel__split-call-to-action">
    <a 
        class="panel__call-to-action" 
        tabindex="-1" 
        href="{{ $paginator->previousPageUrl() }}"
        @if($paginator->onFirstPage())
            disabled
        @endif
    >
        @if(!$paginator->onFirstPage())
            Previous
        @endif
    </a>
    <a 
        class="panel__call-to-action" 
        href="{{ $paginator->nextPageUrl() }}"
        @if(!$paginator->hasMorePages())
            disabled
        @endif
    >
        @if ($paginator->hasMorePages())
            Next
        @endif
    </a>
</div>
@else
</div>
@endif
