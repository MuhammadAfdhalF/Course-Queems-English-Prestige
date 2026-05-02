<x-admin.table-card>
    <form
        x-data="{
            dayRange: '',
            openTime: '',
            closeTime: '',
            operationalHours: @js(old('operational_hours', $contact?->operational_hours)),

            updateOperationalHours() {
                if (this.dayRange && this.openTime && this.closeTime) {
                    this.operationalHours = `${this.dayRange}, ${this.openTime} - ${this.closeTime}`;
                }
            }
        }"
        action="{{ route('admin.cms.contact.save') }}"
        method="POST"
        class="space-y-6 p-6">
        @csrf

        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Contact Information
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Manage the main contact, operational hours, address, and map displayed on the contact page.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-admin.form.input
                label="WhatsApp Number"
                name="whatsapp_label"
                id="whatsapp_label"
                :value="old('whatsapp_label', $contact?->whatsapp_label)"
                placeholder="Example: 0812 3456 7890" />

            <x-admin.form.input
                label="WhatsApp Link"
                name="whatsapp_link_preview"
                id="whatsapp_link_preview"
                :value="$contact?->whatsapp_link"
                placeholder="Automatically generated after save"
                disabled />

            <x-admin.form.input
                label="Email Address"
                name="email_label"
                id="email_label"
                type="email"
                :value="old('email_label', $contact?->email_label)"
                placeholder="Example: hello@queensenglish.com" />

            <x-admin.form.input
                label="Email Link"
                name="email_link_preview"
                id="email_link_preview"
                :value="$contact?->email_link"
                placeholder="Automatically generated after save"
                disabled />

            <div class="space-y-3 md:col-span-2">
                <label class="block text-sm font-bold text-slate-700">
                    Operational Hours
                </label>

                <input
                    type="hidden"
                    name="operational_hours"
                    x-model="operationalHours">

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label for="day_range" class="mb-2 block text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Day Range
                        </label>

                        <select
                            id="day_range"
                            x-model="dayRange"
                            @change="updateOperationalHours()"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">
                            <option value="">Select day range</option>
                            <option value="Monday - Friday">Monday - Friday</option>
                            <option value="Monday - Saturday">Monday - Saturday</option>
                            <option value="Monday - Sunday">Monday - Sunday</option>
                            <option value="Every Day">Every Day</option>
                        </select>
                    </div>

                    <div>
                        <label for="open_time" class="mb-2 block text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Open Time
                        </label>

                        <input
                            id="open_time"
                            type="time"
                            x-model="openTime"
                            @change="updateOperationalHours()"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label for="close_time" class="mb-2 block text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                            Close Time
                        </label>

                        <input
                            id="close_time"
                            type="time"
                            x-model="closeTime"
                            @change="updateOperationalHours()"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-4 focus:ring-blue-100">
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">
                        Preview
                    </p>

                    <p
                        class="mt-1 text-sm font-semibold text-slate-700"
                        x-text="operationalHours || 'Operational hours will be generated automatically.'">
                    </p>
                </div>

                @error('operational_hours')
                <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <x-admin.form.input
                label="Latitude"
                name="latitude"
                id="latitude"
                type="number"
                step="0.00000001"
                :value="old('latitude', $contact?->latitude)"
                placeholder="Example: -6.20000000" />

            <x-admin.form.input
                label="Longitude"
                name="longitude"
                id="longitude"
                type="number"
                step="0.00000001"
                :value="old('longitude', $contact?->longitude)"
                placeholder="Example: 106.81666600" />
        </div>

        <x-admin.form.textarea
            label="Address"
            name="address"
            id="address"
            :value="old('address', $contact?->address)"
            placeholder="Write the full address here..."
            rows="4" />

        <x-admin.form.textarea
            label="Google Maps Embed URL"
            name="map_embed_url"
            id="map_embed_url"
            :value="old('map_embed_url', $contact?->map_embed_url)"
            placeholder="Paste Google Maps embed URL here..."
            rows="4" />

        <x-admin.form.checkbox
            label="Active"
            name="is_active"
            id="is_active"
            :checked="old('is_active', $contact?->is_active ?? true)" />

        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-[var(--color-brand-blue)] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                Save Contact Information
            </button>
        </div>
    </form>
</x-admin.table-card>