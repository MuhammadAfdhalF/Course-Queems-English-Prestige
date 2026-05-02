<x-admin.modal
    model="createModalOpen"
    title="Add Social Link"
    subtitle="Create a social media link for the contact page."
    size="lg">
    <form
        id="createSocialLinkForm"
        action="{{ route('admin.cms.contact.social-links.store') }}"
        method="POST"
        class="space-y-6">
        @csrf

        <input type="hidden" name="_form_type" value="create_social_link">

        <x-admin.form.select
            label="Platform"
            name="platform"
            id="create_platform"
            :value="old('_form_type') === 'create_social_link' ? old('platform') : ''"
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
            id="create_label"
            :value="old('_form_type') === 'create_social_link' ? old('label') : ''"
            placeholder="Example: @queensenglish" />

        <x-admin.form.input
            label="URL"
            name="url"
            id="create_url"
            type="url"
            :value="old('_form_type') === 'create_social_link' ? old('url') : ''"
            placeholder="Example: https://instagram.com/queensenglish"
            :required="true" />

        <x-admin.form.input
            label="Icon"
            name="icon"
            id="create_icon"
            :value="old('_form_type') === 'create_social_link' ? old('icon') : ''"
            placeholder="Optional. Example: instagram" />

        <x-admin.form.input
            label="Sort Order"
            name="sort_order"
            id="create_sort_order"
            type="number"
            min="0"
            :value="old('_form_type') === 'create_social_link' ? old('sort_order', $nextSortOrder) : $nextSortOrder" />

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="create_is_active"
            :checked="old('_form_type') === 'create_social_link' ? (bool) old('is_active', true) : true" />
    </form>

    <x-slot:footer>
        <x-admin.modal-actions
            cancel-action="createModalOpen = false"
            submit-form="createSocialLinkForm"
            submit-label="Save Social Link" />
    </x-slot:footer>
</x-admin.modal>