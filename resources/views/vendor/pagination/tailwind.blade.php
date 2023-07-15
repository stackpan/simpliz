@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="w-full flex flex-col gap-3 sm:hidden">
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-6 py-1 bg-gray-400 text-white text-md sm:text-lg font-bold text-center">
                    {{ __('Next') }}
                </a>
            @endif

            @if (!$paginator->onFirstPage())
                <a href="{{ $paginator->previousPageUrl() }}" class="px-6 py-1 bg-gray-200 text-gray-700 text-md sm:text-lg font-bold text-center">
                    {{ __('Prev') }}
                </a>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <span class="inline-flex sm:items-center gap-2">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="px-5 py-0.5 bg-gray-200 text-md text-gray-400 sm:text-lg font-bold text-center" aria-hidden="true">
                                {{ __('Prev') }}
                            </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-5 py-0.5 bg-gray-200 text-md text-gray-700 sm:text-lg font-bold text-center" aria-label="{{ __('pagination.previous') }}">
                            {{ __('Prev') }}
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                                <span class="px-6 py-0.5 bg-gray-200 text-md text-gray-700 sm:text-lg font-bold text-center">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="px-3 py-0.5 bg-gray-400 text-md sm:text-lg font-bold text-center">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-0.5 bg-gray-200 text-md text-gray-700 sm:text-lg font-bold text-center" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-5 py-0.5 bg-gray-200 text-md text-gray-700 sm:text-lg font-bold text-center" aria-label="{{ __('pagination.next') }}">
                            {{ __('Next') }}
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="px-5 py-0.5 bg-gray-200 text-md text-gray-400 sm:text-lg font-bold text-center" aria-hidden="true">
                            {{ __('Next') }}
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
