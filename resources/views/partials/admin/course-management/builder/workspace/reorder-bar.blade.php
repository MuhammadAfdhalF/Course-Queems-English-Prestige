<div x-show="reorderMode" x-cloak class="space-y-4 rounded-2xl border-2 border-amber-300 bg-amber-50/60 p-4 sm:p-5 shadow-sm">
    <div class="flex items-center justify-between border-b border-amber-200/80 pb-3">
        <div class="flex items-center gap-2">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500 text-white font-bold text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                </svg>
            </span>
            <div>
                <h4 class="text-sm font-bold text-amber-900" x-text="reorderTitle || 'Reorder Items'"></h4>
                <p class="text-xs text-amber-700">Drag items or use arrow controls to change order. Click Save when finished.</p>
            </div>
        </div>

        <button type="button" @click="discardReorder()" class="text-xs font-bold text-amber-800 hover:text-amber-950 underline">
            Cancel
        </button>
    </div>

    {{-- Conflict & Validation Error Banner --}}
    <div x-show="reorderError" x-cloak class="rounded-xl border p-3.5 text-xs font-medium flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-xs" :class="reorderConflict ? 'border-amber-400 bg-amber-100 text-amber-900' : 'border-red-200 bg-red-50 text-red-700'">
        <div class="flex items-start gap-2">
            <svg x-show="reorderConflict" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span x-text="reorderError"></span>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button x-show="reorderConflict" type="button" @click="reloadLatestOrder()" class="rounded-lg bg-amber-600 px-3 py-1 text-xs font-bold text-white shadow-xs hover:bg-amber-700 transition">
                Reload Latest Order
            </button>
            <button type="button" @click="reorderError = null; reorderConflict = false" class="font-bold hover:underline" :class="reorderConflict ? 'text-amber-800' : 'text-red-800'">Dismiss</button>
        </div>
    </div>

    {{-- Reorder Items List --}}
    <div class="space-y-2">
        <template x-for="(item, idx) in reorderItems" :key="item.id">
            <div
                draggable="true"
                @dragstart="handleDragStart(idx, $event)"
                @dragover="handleDragOver(idx, $event)"
                @drop="handleDrop(idx, $event)"
                class="flex items-center justify-between gap-3 rounded-xl border border-amber-200 bg-white p-3 shadow-xs transition hover:border-amber-400 hover:shadow-sm cursor-grab active:cursor-grabbing">

                <div class="flex items-center gap-3 min-w-0">
                    {{-- Drag Handle --}}
                    <span class="text-amber-400 hover:text-amber-600 p-1 flex-shrink-0" title="Drag to reorder" aria-label="Drag handle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </span>

                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-amber-100 text-xs font-black text-amber-900 flex-shrink-0" x-text="idx + 1"></span>

                    <span class="text-xs font-bold text-slate-800 truncate max-w-xs sm:max-w-md" x-text="item.title || item.name || item.question || ('Item #' + item.id)"></span>
                </div>

                {{-- Move Up / Move Down Controls --}}
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button
                        type="button"
                        @click="moveReorderItemToBoundary(idx, 'top')"
                        :disabled="idx === 0"
                        title="Move to Top"
                        aria-label="Move to Top"
                        class="rounded p-1 text-slate-400 hover:bg-amber-100 hover:text-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 disabled:opacity-30 disabled:hover:bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        @click="moveReorderItem(idx, -1)"
                        :disabled="idx === 0"
                        title="Move Up"
                        aria-label="Move Up"
                        class="rounded p-1 text-slate-400 hover:bg-amber-100 hover:text-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 disabled:opacity-30 disabled:hover:bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        @click="moveReorderItem(idx, 1)"
                        :disabled="idx === reorderItems.length - 1"
                        title="Move Down"
                        aria-label="Move Down"
                        class="rounded p-1 text-slate-400 hover:bg-amber-100 hover:text-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 disabled:opacity-30 disabled:hover:bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        @click="moveReorderItemToBoundary(idx, 'bottom')"
                        :disabled="idx === reorderItems.length - 1"
                        title="Move to Bottom"
                        aria-label="Move to Bottom"
                        class="rounded p-1 text-slate-400 hover:bg-amber-100 hover:text-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 disabled:opacity-30 disabled:hover:bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7M19 5l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- Sticky Footer Bar --}}
    <div class="flex items-center justify-between border-t border-amber-200/80 pt-3">
        <button
            type="button"
            @click="discardReorder()"
            :disabled="reorderSaving"
            class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-xs hover:bg-slate-50 transition focus:outline-none focus:ring-2 focus:ring-amber-500">
            Discard
        </button>

        <div class="flex items-center gap-2">
            <button
                x-show="reorderConflict"
                type="button"
                @click="reloadLatestOrder()"
                class="rounded-xl border border-amber-500 bg-amber-100 px-4 py-2 text-xs font-bold text-amber-900 hover:bg-amber-200 transition">
                Reload Latest Order
            </button>

            <button
                type="button"
                @click="saveReorder()"
                :disabled="reorderSaving"
                class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-amber-500">
                <svg x-show="reorderSaving" class="h-3.5 w-3.5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="reorderSaving ? 'Saving Order...' : 'Save Order'"></span>
            </button>
        </div>
    </div>
</div>
