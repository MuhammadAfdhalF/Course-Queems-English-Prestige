@extends('layouts.student')

@section('content')
<section class="mx-auto max-w-5xl space-y-10 py-8">
    <div class="reveal text-center">
        <h1 class="text-4xl font-bold text-slate-900 lg:text-5xl">
            Testimoni
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 text-slate-500">
            Berikan feedback Anda untuk membantu kami berkembang dan dapatkan
            akses penuh ke sertifikat kursus Anda.
        </p>

        <div class="mt-6 inline-flex items-center gap-2 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-2 text-sm font-semibold text-[var(--color-brand-gold)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8h.01M11 12h1v4h1" />
            </svg>
            Testimoni Anda diperlukan untuk membuka sertifikat digital.
        </div>
    </div>

    <div class="reveal reveal-delay-1">
        <x-student.testimonial-form-card />
    </div>

    <div class="reveal reveal-delay-2">
        <x-student.testimonial-history-table />
    </div>

    <p class="reveal reveal-delay-3 text-center text-sm text-slate-500">
        Mengalami kendala teknis?
        <a href="#" class="font-semibold text-[var(--color-brand-blue)] hover:underline">
            Hubungi Tim IT Support kami
        </a>
    </p>
</section>
@endsection