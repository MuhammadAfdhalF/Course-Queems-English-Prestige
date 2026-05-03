<x-admin.modal
    model="editModalOpen"
    title="Edit Question"
    subtitle="Update the selected multiple-choice question."
    size="xl">
    <template x-if="selectedQuestion">
        <form
            id="editFreeTestQuestionForm"
            :action="selectedQuestion.update_url"
            method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">

            <x-admin.form.textarea
                label="Question"
                name="question"
                id="edit_question"
                x-model="selectedQuestion.question"
                rows="4"
                :required="true" />

            <div class="grid gap-6 md:grid-cols-2">
                <x-admin.form.input
                    label="Option A"
                    name="option_a"
                    id="edit_option_a"
                    x-model="selectedQuestion.option_a"
                    :required="true" />

                <x-admin.form.input
                    label="Option B"
                    name="option_b"
                    id="edit_option_b"
                    x-model="selectedQuestion.option_b"
                    :required="true" />

                <x-admin.form.input
                    label="Option C"
                    name="option_c"
                    id="edit_option_c"
                    x-model="selectedQuestion.option_c"
                    :required="true" />

                <x-admin.form.input
                    label="Option D"
                    name="option_d"
                    id="edit_option_d"
                    x-model="selectedQuestion.option_d"
                    :required="true" />
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <x-admin.form.select
                    label="Correct Answer"
                    name="correct_answer"
                    id="edit_correct_answer"
                    x-model="selectedQuestion.correct_answer"
                    :options="[
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                    ]"
                    :required="true" />

                <x-admin.form.input
                    label="Score"
                    name="score"
                    id="edit_score"
                    type="number"
                    min="1"
                    x-model="selectedQuestion.score" />

                <x-admin.form.input
                    label="Sort Order"
                    name="sort_order"
                    id="edit_sort_order"
                    type="number"
                    min="0"
                    x-model="selectedQuestion.sort_order" />
            </div>

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedQuestion.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editFreeTestQuestionForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>