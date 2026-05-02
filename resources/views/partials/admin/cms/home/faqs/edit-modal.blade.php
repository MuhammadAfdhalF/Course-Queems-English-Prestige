<x-admin.modal
    model="editModalOpen"
    title="Edit FAQ"
    subtitle="Update the selected FAQ."
    size="lg">
    <template x-if="selectedFaq">
        <form
            id="editFaqForm"
            :action="selectedFaq.update_url"
            method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.input
                label="Question"
                name="question"
                id="edit_question"
                x-model="selectedFaq.question"
                :required="true" />

            <x-admin.form.textarea
                label="Answer"
                name="answer"
                id="edit_answer"
                x-model="selectedFaq.answer"
                rows="5" />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="edit_sort_order"
                type="number"
                min="0"
                x-model="selectedFaq.sort_order" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedFaq.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <button
            type="button"
            @click="editModalOpen = false"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </button>

        <button
            type="submit"
            form="editFaqForm"
            class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            Save Changes
        </button>
    </x-slot:footer>
</x-admin.modal>