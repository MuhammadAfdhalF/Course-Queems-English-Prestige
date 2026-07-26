<x-admin.data-table>
    <x-slot:head>
        <th class="px-6 py-4">Participant</th>
        <th class="px-6 py-4">Contact</th>
        <th class="px-6 py-4">Free Test</th>
        <th class="px-6 py-4">Score</th>
        <th class="px-6 py-4">Recommendation</th>
        <th class="px-6 py-4">Submitted At</th>
        <th class="px-6 py-4 text-center">Action</th>
    </x-slot:head>

    @forelse ($results as $result)
    @php
    $freeTest = $result->freeTest;
    $category = $freeTest?->categoryRelation?->name;

    $whatsappRaw = $result->participant_whatsapp;
    $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsappRaw ?? '');

    if (str_starts_with($cleanWhatsapp, '0')) {
    $cleanWhatsapp = '62' . substr($cleanWhatsapp, 1);
    }

    $whatsappUrl = filled($cleanWhatsapp)
    ? 'https://wa.me/' . $cleanWhatsapp . '?text=' . urlencode('Hello ' . ($result->participant_name ?: 'there') . ', thank you for taking the Queens English Prestige free test. We would like to help you with the next learning recommendation.')
    : null;

    $emailUrl = filled($result->participant_email)
    ? 'mailto:' . $result->participant_email
    : null;

    $submittedAt = $result->submitted_at
    ? $result->submitted_at->format('M d, Y H:i')
    : $result->created_at?->format('M d, Y H:i');

    $passingScore = $result->passing_score ?? $freeTest?->passing_score;
    @endphp

    <tr class="text-sm text-slate-700">
        <td class="max-w-xs px-6 py-4">
            <p class="font-semibold text-slate-900">
                {{ $result->participant_name ?: 'Unknown Participant' }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                Result ID: #{{ $result->id }}
            </p>
        </td>

        <td class="max-w-xs px-6 py-4">
            <div class="space-y-1">
                <p class="truncate text-sm text-slate-700">
                    {{ $result->participant_email ?: '-' }}
                </p>

                <p class="truncate text-sm text-slate-500">
                    {{ $result->participant_whatsapp ?: '-' }}
                </p>
            </div>
        </td>

        <td class="max-w-sm px-6 py-4">
            <p class="font-semibold text-slate-900">
                {{ $freeTest?->title ?: 'Deleted Free Test' }}
            </p>

            <div class="mt-2 flex flex-wrap items-center gap-2">
                @if ($category)
                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                    {{ $category }}
                </span>
                @endif

                @if ($freeTest)
                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    {{ $freeTest->total_questions }} questions
                </span>
                @endif
            </div>
        </td>

        <td class="px-6 py-4">
            <div class="space-y-2">
                <span class="inline-flex rounded-full bg-[#fff6da] px-3 py-1 text-xs font-extrabold text-[var(--color-brand-gold)]">
                    {{ $result->total_score }} Score
                </span>

                @if ($passingGrade)
                <p class="text-xs text-slate-400">
                    Passing: {{ $passingGrade }}%
                </p>
                @endif
            </div>
        </td>

        <td class="max-w-md px-6 py-4">
            <p class="line-clamp-2 text-sm leading-6 text-slate-500">
                {{ $result->recommendation ?: '-' }}
            </p>
        </td>

        <td class="px-6 py-4">
            <p class="whitespace-nowrap text-sm font-semibold text-slate-700">
                {{ $submittedAt ?: '-' }}
            </p>
        </td>

        <td class="px-6 py-4">
            <div class="flex justify-center gap-2">
                @if ($whatsappUrl)
                <a
                    href="{{ $whatsappUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    title="Contact via WhatsApp"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition hover:bg-emerald-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 10.5A5.5 5.5 0 1112.5 16c-.95 0-1.85-.24-2.64-.66L6 16l.7-3.6A5.48 5.48 0 017 10.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 9.5c.2 1 1.3 2.2 2.3 2.5.4.1.8 0 1.1-.2l.8-.5" />
                    </svg>
                </a>
                @endif

                @if ($emailUrl)
                <a
                    href="{{ $emailUrl }}"
                    title="Send Email"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition hover:bg-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l8.4 5.6a1.1 1.1 0 001.2 0L21 8" />
                        <rect x="3" y="6" width="18" height="12" rx="2" stroke-width="1.8" />
                    </svg>
                </a>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <x-admin.empty-state
        colspan="7"
        title="No free test results yet"
        description="Submitted free test results will appear here." />
    @endforelse
</x-admin.data-table>