@php
if (!isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <!-- Mobile View -->
            <div class="flex justify-between flex-1 sm:hidden">
                <!-- ... (kode mobile view tetap sama) ... -->
            </div>

            <!-- Desktop View -->
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-600 leading-5">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rtl:flex-row-reverse rounded-md shadow-sm">
                        <!-- Previous Button -->
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-300 bg-white border border-blue-300 cursor-default rounded-l-md leading-5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @else
                            <button type="button"
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-l-md leading-5 hover:bg-blue-50 focus:z-10 focus:outline-none focus:ring-0 transition-none">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $current = $paginator->currentPage();
                            $last = $paginator->lastPage();
                            $window = 1; // Jumlah halaman di sekitar current
                        @endphp

                        <!-- First Page -->
                        @if ($current > $window + 2)
                            <button type="button"
                                    wire:click="gotoPage(1, '{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5 hover:bg-blue-50 focus:z-10 focus:outline-none focus:ring-0 transition-none">
                                1
                            </button>

                            @if ($current > $window + 3)
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5">
                                    ...
                                </span>
                            @endif
                        @endif

                        <!-- Middle Pages -->
                        @foreach ($paginator->getUrlRange(max(1, $current - $window), min($last, $current + $window)) as $page => $url)
                            @if ($page == $current)
                                <span aria-current="page" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default leading-5">
                                    {{ $page }}
                                </span>
                            @else
                                <button type="button"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5 hover:bg-blue-50 focus:z-10 focus:outline-none focus:ring-0 transition-none">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach

                        <!-- Last Page -->
                        @if ($current < $last - $window - 1)
                            @if ($current < $last - $window - 2)
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5">
                                    ...
                                </span>
                            @endif

                            <button type="button"
                                    wire:click="gotoPage({{ $last }}, '{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5 hover:bg-blue-50 focus:z-10 focus:outline-none focus:ring-0 transition-none">
                                {{ $last }}
                            </button>
                        @endif

                        <!-- Next Button -->
                        @if ($paginator->hasMorePages())
                            <button type="button"
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-r-md leading-5 hover:bg-blue-50 focus:z-10 focus:outline-none focus:ring-0 transition-none">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @else
                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-300 bg-white border border-blue-300 cursor-default rounded-r-md leading-5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
