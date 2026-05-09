@php
$aboutTitle = $aboutUs?->title ?: 'The Beginning of Our Journey';
$aboutDescription = $aboutUs?->description;
$aboutImage = $aboutUs?->image
? asset('storage/' . $aboutUs->image)
: asset('images/logo-queens-english.png');
@endphp

<section class="bg-white">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
        <div class="max-w-2xl">
            <h2 class="reveal text-2xl font-bold leading-tight text-[var(--color-brand-blue)] md:text-3xl">
                {{ $aboutTitle }}
            </h2>

            <div class="reveal reveal-delay-1 mt-6 space-y-5 text-sm leading-8 text-slate-600 md:text-base">
                @if (filled($aboutDescription))
                <div class="rich-text-content">
                    {!! $aboutDescription !!}
                </div>
                @else
                <p>
                    Queens English Prestige is built to make English learning clear, structured,
                    and accessible—both online and offline. Our platform combines a modern company
                    profile for program information with a dedicated student area where learners can
                    study independently through modules, practice exercises, and a final exam.
                </p>

                <p>
                    Enrollment is simple: choose a program on the website and place an order.
                    Payments are confirmed manually via WhatsApp, and your order status is managed
                    by our admin team. Once approved, you gain full access to your course content
                    and learning progress.
                </p>
                @endif
            </div>
        </div>

        <div class="reveal reveal-delay-2 flex justify-center lg:justify-end">
            <div class="motion-card w-full max-w-md rounded-none border border-slate-300 bg-white p-6 shadow-sm">
                <img
                    src="{{ $aboutImage }}"
                    alt="{{ $aboutTitle }}"
                    class="motion-image mx-auto h-auto w-full max-w-[320px] object-contain">
            </div>
        </div>
    </div>
</section>