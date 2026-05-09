@if (($posts ?? null) && $posts->hasPages())
@php
$currentPage = $posts->currentPage();
$lastPage = $posts->lastPage();

$startPage = max(1, $currentPage - 2);
$endPage = min($lastPage, $currentPage + 2);

if ($currentPage <= 3) {
    $endPage=min($lastPage, 5);
    }

    if ($currentPage>= $lastPage - 2) {
    $startPage = max(1, $lastPage - 4);
    }
    @endphp

    <section class="bg-[#f8f8f6]">
        <div class="mx-auto max-w-7xl px-4 pb-16 lg:px-8 lg:pb-20">
            <div class="reveal flex flex-wrap items-center justify-center gap-3">
                @if ($posts->onFirstPage())
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
                @else
                <a
                    href="{{ $posts->previousPageUrl() }}"
                    class="motion-button inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-400 transition hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                @endif

                @if ($startPage > 1)
                <a
                    href="{{ $posts->url(1) }}"
                    class="motion-button inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-200 bg-white text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                    1
                </a>

                @if ($startPage > 2)
                <span class="px-1 text-slate-400">...</span>
                @endif
                @endif

                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page===$currentPage)
                    <span class="motion-button inline-flex h-10 w-10 items-center justify-center rounded-md bg-[#2457E6] text-sm font-bold text-white">
                    {{ $page }}
                    </span>
                    @else
                    <a
                        href="{{ $posts->url($page) }}"
                        class="motion-button inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-200 bg-white text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                        {{ $page }}
                    </a>
                    @endif
                    @endfor

                    @if ($endPage < $lastPage)
                        @if ($endPage < $lastPage - 1)
                        <span class="px-1 text-slate-400">...</span>
                        @endif

                        <a
                            href="{{ $posts->url($lastPage) }}"
                            class="motion-button inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-200 bg-white text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                            {{ $lastPage }}
                        </a>
                        @endif

                        @if ($posts->hasMorePages())
                        <a
                            href="{{ $posts->nextPageUrl() }}"
                            class="motion-button inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="motion-link-arrow h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        @else
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                        @endif
            </div>
        </div>
    </section>
    @endif