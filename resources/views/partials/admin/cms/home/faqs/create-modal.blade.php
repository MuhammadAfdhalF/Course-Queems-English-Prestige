<x-admin.modal
    model="createModalOpen"
    title="Add FAQ"
    subtitle="Create a frequently asked question."
    size="lg">
    <form
        id="createFaqForm"
        action="{{ route('admin.cms.faqs.store') }}"
        method="POST"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.input
            label="Question"
            name="question"
            id="create_question"
            :value="old('_form_type') === 'create' ? old('question') : ''"
            placeholder="Example: How do I join the course?"
            :required="true" />

        <x-admin.form.textarea
            label="Answer"
            name="answer"
            id="create_answer"
            :value="old('_form_type') === 'create' ? old('answer') : ''"
            placeholder="Write the answer here..."
            rows="5" />

        <x-admin.form.input
            label="Sort Order"
            name="sort_order"
            id="create_sort_order"
            type="number"
            min="0"
            :value="old('_form_type') === 'create' ? old('sort_order', $nextSortOrder) : $nextSortOrder" />

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="create_is_active"
            :checked="old('_form_type') === 'create' ? (bool) old('is_active', true) : true" />
    </form>

    <x-slot:footer>
        <button
            type="button"
            @click="createModalOpen = false"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </button>

        <button
            type="submit"
            form="createFaqForm"
            class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
            Save FAQ
        </button>
    </x-slot:footer>
</x-admin.modal>