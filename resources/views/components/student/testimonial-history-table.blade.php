@php
$items = [
[
'course' => 'IELTS Mastery: Band 7.5+',
'track' => 'Academic Track',
'date' => '12 Okt 2023',
'rating' => 5,
'status' => 'Published',
'statusClass' => 'bg-emerald-50 text-emerald-600',
],
[
'course' => 'Queens English Prestige (Layanan)',
'track' => 'Company Feedback',
'date' => '05 Sep 2023',
'rating' => 5,
'status' => 'Published',
'statusClass' => 'bg-emerald-50 text-emerald-600',
],
[
'course' => 'Business English Communication',
'track' => 'Professional Track',
'date' => '20 Agu 2023',
'rating' => 4,
'status' => 'Reviewing',
'statusClass' => 'bg-yellow-50 text-[var(--color-brand-gold)]',
],
];
@endphp

<div>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <h2 class="text-3xl font-bold text-slate-900">
            Riwayat Testimoni Saya
        </h2>

        <p class="text-sm font-semibold text-slate-400">
            3 Total Submissions
        </p>
    </div>

    <div class="overflow-hidden rounded-[20px] border border-slate-200 bg-white shadow-sm">
        <div class="hidden grid-cols-[1.6fr_0.6fr_0.5fr_0.6fr_0.3fr] gap-4 border-b border-slate-100 bg-slate-50 px-6 py-4 text-xs font-bold uppercase tracking-[0.16em] text-slate-400 md:grid">
            <div>Kursus / Subjek</div>
            <div>Tanggal</div>
            <div>Rating</div>
            <div>Status</div>
            <div class="text-right">Aksi</div>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($items as $item)
            <div class="grid gap-4 px-6 py-5 md:grid-cols-[1.6fr_0.6fr_0.5fr_0.6fr_0.3fr] md:items-center">
                <div>
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $item['course'] }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $item['track'] }}
                    </p>
                </div>

                <div class="text-sm font-medium text-slate-500">
                    {{ $item['date'] }}
                </div>

                <div class="text-sm text-[var(--color-brand-gold)]">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $item['rating'] ? '★' : '☆' }}
                        @endfor
                        </div>

                        <div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ $item['statusClass'] }}">
                                • {{ $item['status'] }}
                            </span>
                        </div>

                        <div class="text-right">
                            <a href="#" class="inline-flex items-center justify-center text-[var(--color-brand-blue)] hover:opacity-80">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>