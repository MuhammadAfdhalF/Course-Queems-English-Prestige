<x-admin.modal
    model="editModalOpen"
    title="Edit Social Link"
    subtitle="Update the selected social media link."
    size="lg">
    <template x-if="selectedSocialLink">
        <form
            id="editSocialLinkForm"
            :action="selectedSocialLink.update_url"
            method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit_social_link">

            <x-admin.form.select
                label="Platform"
                name="platform"
                id="edit_platform"
                x-model="selectedSocialLink.platform"
                :options="[
                    'instagram' => 'Instagram',
                    'tiktok' => 'TikTok',
                    'facebook' => 'Facebook',
                    'youtube' => 'YouTube',
                    'linkedin' => 'LinkedIn',
                    'other' => 'Other',
                ]"
                placeholder="Select platform"
                :required="true" />

            <x-admin.form.input
                label="Label"
                name="label"
                id="edit_label"
                x-model="selectedSocialLink.label" />

            <x-admin.form.input
                label="URL"
                name="url"
                id="edit_url"
                type="url"
                x-model="selectedSocialLink.url"
                :required="true" />

            <x-admin.form.input
                label="Icon"
                name="icon"
                id="edit_icon"
                x-model="selectedSocialLink.icon" />

            <x-admin.form.input
                label="Sort Order"
                name="sort_order"
                id="edit_sort_order"
                type="number"
                min="0"
                x-model="selectedSocialLink.sort_order" />

            <x-admin.form.checkbox
                label="Active"
                name="is_active"
                id="edit_is_active"
                x-model="selectedSocialLink.is_active" />
        </form>
    </template>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="editModalOpen = false"
            submit-form="editSocialLinkForm"
            submit-label="Save Changes" />
    </x-slot:footer>
</x-admin.modal>