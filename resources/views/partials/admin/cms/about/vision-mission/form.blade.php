@php
$missionValues = old('missions');

if (! is_array($missionValues)) {
$missionValues = $visionMission?->missionItems
? $visionMission->missionItems->pluck('content')->toArray()
: [];
}

if (count($missionValues) === 0) {
$missionValues = [''];
}
@endphp

<x-admin.table-card>
    <form
        x-data="{
            missions: @js($missionValues),

            addMission() {
                this.missions.push('');
            },

            removeMission(index) {
                this.missions.splice(index, 1);

                if (this.missions.length === 0) {
                    this.missions.push('');
                }
            }
        }"
        action="{{ route('admin.cms.vision-mission.save') }}"
        method="POST"
        class="space-y-6 p-6">
        @csrf

        <x-admin.form.textarea
            label="Vision"
            name="vision"
            id="vision"
            :value="old('vision', $visionMission?->vision)"
            placeholder="Write the institution vision here..."
            rows="6" />

        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-bold text-slate-700">
                    Mission Points
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Add mission points one by one. They will be displayed as a numbered list on the website.
                </p>
            </div>

            <template x-for="(mission, index) in missions" :key="index">
                <div class="flex gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-sm font-bold text-slate-500">
                        <span x-text="index + 1"></span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <textarea
                            :name="`missions[${index}]`"
                            x-model="missions[index]"
                            rows="2"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100"
                            placeholder="Write mission point here..."></textarea>
                    </div>

                    <button
                        type="button"
                        @click="removeMission(index)"
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100"
                        title="Remove mission point">
                        <x-admin.icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </template>

            <button
                type="button"
                @click="addMission()"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                <x-admin.icon name="plus" class="h-4 w-4" />
                <span>Add Mission Point</span>
            </button>

            @error('missions')
            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror

            @error('missions.*')
            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="is_active"
            :checked="old('is_active', $visionMission?->is_active ?? true)" />

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
            <a
                href="{{ route('admin.cms.about.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                Save Changes
            </button>
        </div>
    </form>
</x-admin.table-card>