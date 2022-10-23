<nav role="navigation" aria-label="Pagination Navigation" class="w-full bg-slate-50 rounded-b flex justify-between items-center">
    @if ($paginator->onFirstPage())
        <div class="p-3 text-center text-gray-400 cursor-not-allowed text-sm">
            {!! __('pagination.previous') !!}
        </div>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="font-semibold block p-3 text-center text-gray-700 hover:bg-gray-200 text-sm">
                {!! __('pagination.previous') !!}
        </a>
    @endif

    <p class="text-sm text-gray-700 leading-5">
        {!! __('Showing') !!}
        @if ($paginator->firstItem())
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            {!! __('to') !!}
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
        @else
            {{ $paginator->count() }}
        @endif
        {!! __('of') !!}
        <span class="font-medium">{{ $paginator->total() }}</span>
        {!! __('results') !!}
    </p>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="prev" class="font-semibold block p-3 text-center text-gray-700 hover:bg-gray-200 text-sm">
                {!! __('pagination.next') !!}
            </a>
        @else
            <div class="p-3 text-center text-gray-400 cursor-not-allowed text-sm">
                {!! __('pagination.next') !!}
            </div>
        @endif
</nav>

