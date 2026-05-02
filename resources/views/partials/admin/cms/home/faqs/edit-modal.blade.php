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
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editFaqForm"
            submit-label="Save Changes" />
    </x-slot:footer>
    
</x-admin.modal>