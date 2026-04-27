<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
            Full Name
        </label>

        <input
            type="text"
            :value="selectedStudent.name"
            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-bold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
    </div>

    <div>
        <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
            Email Address
        </label>

        <input
            type="email"
            :value="selectedStudent.email"
            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-bold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">

        <p class="mt-2 text-xs font-semibold text-[var(--color-brand-gold)]">
            ⚠ Changing email may affect login credentials.
        </p>
    </div>

    <div>
        <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
            WhatsApp / Phone
        </label>

        <input
            type="text"
            :value="selectedStudent.whatsapp"
            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-bold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
    </div>

    <div>
        <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
            Place & Date of Birth
        </label>

        <input
            type="text"
            value="New York, 12 Jan 1995"
            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-bold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
    </div>

    <div>
        <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
            Gender
        </label>

        <select class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-bold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
            <option>Male</option>
            <option>Female</option>
        </select>
    </div>

    <div>
        <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
            Profession
        </label>

        <input
            type="text"
            value="Frontend Developer"
            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-bold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
    </div>

    <div>
        <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
            Institution
        </label>

        <input
            type="text"
            value="Tech Academy Global"
            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-bold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
    </div>

    <div>
        <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
            Social Media
        </label>

        <input
            type="text"
            value="@student_profile"
            class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-bold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">
    </div>

    <div class="sm:col-span-2">
        <label class="mb-2 block text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">
            Full Address
        </label>

        <textarea
            rows="3"
            class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 outline-none transition focus:border-[var(--color-brand-blue)] focus:ring-2 focus:ring-blue-100">123 Broadway, Manhattan, NY 10006, United States</textarea>
    </div>
</div>