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
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <!-- Mobile View -->
        <div class="flex justify-between flex-1 sm:hidden">
            <!-- Mobile buttons can be added here if needed -->
        </div>

        <!-- Desktop View -->
        <div class="w-full flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <p class="text-sm text-gray-600 leading-5">
                    <span>{!! __('Showing') !!}</span>
                    <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                    <span>{!! __('to') !!}</span>
                    <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                    <span>{!! __('of') !!}</span>
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    <span>{!! __('results') !!}</span>
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse rounded-md shadow-sm">
                    {{-- Previous Page --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true">
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
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-l-md leading-5 hover:bg-blue-50 focus:z-10 focus:outline-none transition-none">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $current = $paginator->currentPage();
                        $last = $paginator->lastPage();
                        $window = 1;
                    @endphp

                    {{-- First Page --}}
                    @if ($current > $window + 2)
                        <button type="button"
                                wire:click="gotoPage(1, '{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5 hover:bg-blue-50 focus:z-10 transition-none">
                            1
                        </button>

                        @if ($current > $window + 3)
                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5">
                                ...
                            </span>
                        @endif
                    @endif

                    {{-- Middle Pages --}}
                    @foreach ($paginator->getUrlRange(max(1, $current - $window), min($last, $current + $window)) as $page => $url)
                        @if ($page == $current)
                            <span aria-current="page"
                                  class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default leading-5">
                                {{ $page }}
                            </span>
                        @else
                            <button type="button"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5 hover:bg-blue-50 focus:z-10 transition-none">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach

                    {{-- Last Page --}}
                    @if ($current < $last - $window - 1)
                        @if ($current < $last - $window - 2)
                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5">
                                ...
                            </span>
                        @endif

                        <button type="button"
                                wire:click="gotoPage({{ $last }}, '{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 leading-5 hover:bg-blue-50 focus:z-10 transition-none">
                            {{ $last }}
                        </button>
                    @endif

                    {{-- Next Page --}}
                    @if ($paginator->hasMorePages())
                        <button type="button"
                                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-r-md leading-5 hover:bg-blue-50 focus:z-10 transition-none">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @else
                        <span aria-disabled="true">
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
</div>
