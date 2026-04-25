<section class="relative z-10 -mt-8">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="reveal rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 lg:grid-cols-[1fr_1fr_1fr_auto] lg:items-end">
                <div class="reveal">
                    <label class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Type Course
                    </label>

                    <x-ui.select>
                        <option>All Type Course</option>
                        <option>Online</option>
                        <option>Offline</option>
                    </x-ui.select>
                </div>

                <div class="reveal reveal-delay-1">
                    <label class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Categories Course
                    </label>

                    <x-ui.select>
                        <option>All Categories</option>
                        <option>Academic</option>
                        <option>Business</option>
                        <option>Grammar</option>
                        <option>Speaking</option>
                    </x-ui.select>
                </div>

                <div class="reveal reveal-delay-2">
                    <label class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Categories Course
                    </label>

                    <x-ui.select>
                        <option>All Categories</option>
                        <option>Beginner</option>
                        <option>Intermediate</option>
                        <option>Advanced</option>
                    </x-ui.select>
                </div>

                <div class="reveal reveal-delay-3">
                    <x-ui.button class="w-full px-6 py-3 lg:w-auto">
                        Filter Courses
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</section>