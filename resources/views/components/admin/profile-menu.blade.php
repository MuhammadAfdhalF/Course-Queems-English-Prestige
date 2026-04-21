<div x-data="{ open: false }" class="relative">
    <button
        @click="open = !open"
        type="button"
        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2"
    >
        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-100 text-sm font-semibold text-amber-700">
            JS
        </div>

        <div class="hidden text-right sm:block">
            <p class="text-sm font-semibold text-slate-900">Julian Sterling</p>
            <p class="text-xs text-slate-500">Head Administrator</p>
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4 text-slate-400 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute right-0 z-50 mt-2 w-56 rounded-2xl border border-slate-200 bg-white p-2 shadow-lg"
    >
        <a href="#" class="block rounded-xl px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
        <a href="#" class="block rounded-xl px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">Settings</a>
        <a href="#" class="block rounded-xl px-4 py-3 text-sm text-rose-600 hover:bg-rose-50">Logout</a>
    </div>
</div>