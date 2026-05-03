<x-admin.modal
    model="createModalOpen"
    title="Add Question"
    subtitle="Create a multiple-choice question."
    size="xl">
    <form
        id="createFreeTestQuestionForm"
        action="{{ route('admin.cms.free-tests.questions.store', $freeTest) }}"
        method="POST"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create">

        <x-admin.form.textarea
            label="Question"
            name="question"
            id="create_question"
            :value="old('_form_type') === 'create' ? old('question') : ''"
            placeholder="Write the question here..."
            rows="4"
            :required="true" />

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="Option A"
                name="option_a"
                id="create_option_a"
                :value="old('_form_type') === 'create' ? old('option_a') : ''"
                :required="true" />

            <x-admin.form.input
                label="Option B"
                name="option_b"
                id="create_option_b"
                :value="old('_form_type') === 'create' ? old('option_b') : ''"
                :required="true" />

            <x-admin.form.input
                label="Option C"
                name="option_c"
                id="create_option_c"
                :value="old('_form_type') === 'create' ? old('option_c') : ''"
                :required="true" />

            <x-admin.form.input
                label="Option D"
                name="option_d"
                id="create_option_d"
                :value="old('_form_type') === 'create' ? old('option_d') : ''"
                :required="true" />
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <x-admin.form.select
                label="Correct Answer"
                name="correct_answer"
                id="create_correct_answer"
                :value="old('_form_type') === 'create' ? old('correct_answer') : ''"
                :options="[
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                ]"
                placeholder="Select answer"
                :required="true" />

            <x-admin.form.input
                label="Score"
                name="score"
                id="create_score"
                type="number"
                min="1"
                :value="old('_form_type') === 'create' ? old('score', 1) : 1" />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="create_sort_order"
                type="number"
                min="0"
                :value="old('_form_type') === 'create' ? old('sort_order', $nextSortOrder) : $nextSortOrder" />
        </div>

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="create_is_active"
            :checked="old('_form_type') === 'create' ? (bool) old('is_active', true) : true" />
    </form>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="createModalOpen = false"
            submit-form="createFreeTestQuestionForm"
            submit-label="Save Question" />
    </x-slot:footer>
</x-admin.modal>