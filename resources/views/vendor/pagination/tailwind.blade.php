@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="w-full flex flex-col gap-3 sm:hidden">
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-6 py-1 bg-gray-400 text-white text-md sm:text-lg font-bold text-center">
                    {{ __('Next') }}
                </a>
            @endif

            @if (!$paginator->onFirstPage())
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-6 py-1 bg-gray-200 text-gray-700 text-md sm:text-lg font-bold text-center">
                    {{ __('Prev') }}
                </a>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <span class="inline-flex sm:items-center gap-4">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"
                              aria-hidden="true">
                            <x-button.primary class="btn-disabled btn-sm">{{ __('Prev') }}</x-button.primary>
                            </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                           aria-label="{{ __('pagination.previous') }}">
                            <x-button.primary class="btn-sm">{{ __('Prev') }}</x-button.primary>
                        </a>
                    @endif

                    <div class="flex gap-2">
                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span
                                    class="px-6 py-0.5 bg-gray-200 text-md text-gray-700 sm:text-lg font-bold text-center">{{ $element }}</span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page">
                                        <x-button.primary class="bg-primary btn-sm">{{ $page }}</x-button.primary>
                                    </span>
                                    @else
                                        <a href="{{ $url }}"
                                           aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        <x-button.primary class="btn-sm">{{ $page }}</x-button.primary>
                                    </a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </div>

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                           aria-label="{{ __('pagination.next') }}">
                            <x-button.primary class="btn-sm">{{ __('Next') }}</x-button.primary>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"
                              aria-hidden="true">
                            <x-button.primary class="btn-disabled btn-sm">{{ __('Next') }}</x-button.primary>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
