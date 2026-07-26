<x-admin.modal
    model="editModalOpen"
    title="Edit Free Test"
    subtitle="Update the selected free test."
    size="lg">
    <template x-if="selectedFreeTest">
        <form
            id="editFreeTestForm"
            :action="selectedFreeTest.update_url"
            method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.input
                label="Title"
                name="title"
                id="edit_title"
                x-model="selectedFreeTest.title"
                :required="true" />

            <x-admin.form.select
                label="Category"
                name="free_test_category_id"
                id="edit_free_test_category_id"
                x-model="selectedFreeTest.free_test_category_id"
                :options="$categories->pluck('name', 'id')->toArray()"
                placeholder="Select category" />

            <x-admin.form.textarea
                label="Description"
                name="description"
                id="edit_description"
                x-model="selectedFreeTest.description"
                rows="4" />

            <template x-if="selectedFreeTest.is_locked">
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs font-semibold text-amber-800">
                    Scoring configuration cannot be changed because this free test already has student results.
                </div>
            </template>

            <div class="grid gap-6 md:grid-cols-3">
                <x-admin.form.input
                    label="Duration Minutes"
                    name="duration_minutes"
                    id="edit_duration_minutes"
                    type="number"
                    min="1"
                    x-model="selectedFreeTest.duration_minutes" />

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Result Type <span class="text-rose-500">*</span></label>
                    <select
                        name="result_mode"
                        id="edit_result_mode"
                        x-model="selectedFreeTest.result_mode"
                        :disabled="selectedFreeTest.is_locked"
                        required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none disabled:bg-slate-100 disabled:text-slate-500">
                        <option value="score_only">Score Only</option>
                        <option value="pass_fail">Pass / Fail</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Total Score <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        name="total_score"
                        id="edit_total_score"
                        x-model="selectedFreeTest.total_score"
                        :disabled="selectedFreeTest.is_locked"
                        required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none disabled:bg-slate-100 disabled:text-slate-500" />
                </div>
            </div>

            <div x-show="selectedFreeTest.result_mode === 'pass_fail'" class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Minimum Passing Score <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        :max="selectedFreeTest.total_score"
                        name="passing_score"
                        id="edit_passing_score"
                        x-model="selectedFreeTest.passing_score"
                        :disabled="selectedFreeTest.is_locked"
                        :required="selectedFreeTest.result_mode === 'pass_fail'"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs font-semibold focus:border-blue-500 focus:outline-none disabled:bg-slate-100 disabled:text-slate-500" />
                </div>
            </div>

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedFreeTest.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editFreeTestForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>