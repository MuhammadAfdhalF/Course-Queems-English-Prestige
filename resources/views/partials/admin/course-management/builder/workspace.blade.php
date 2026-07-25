<div class="relative min-h-[400px] rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    {{-- Loading Overlay --}}
    <div
        x-show="loading"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 z-20 flex flex-col items-center justify-center rounded-2xl bg-white/80 backdrop-blur-sm">
        <div class="h-8 w-8 animate-spin rounded-full border-4 border-slate-200 border-t-[var(--color-brand-blue)]"></div>
        <p class="mt-3 text-xs font-semibold text-slate-500">Loading workspace...</p>
    </div>

    {{-- Error State with Retry --}}
    <div
        x-show="error && !loading"
        x-cloak
        class="flex min-h-[300px] flex-col items-center justify-center py-12 text-center">
        <div class="rounded-full bg-rose-50 p-3 text-rose-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="mt-4 text-base font-bold text-slate-800">Failed to Load Workspace</h3>
        <p class="mt-1 text-xs text-slate-500 max-w-sm" x-text="error"></p>
        <button
            type="button"
            @click="retryFetch()"
            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white shadow transition hover:bg-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Retry Loading
        </button>
    </div>

    {{-- Rendered HTML Workspace Partial --}}
    <div
        x-show="!error"
        x-html="workspaceHtml">
    </div>
</div>
