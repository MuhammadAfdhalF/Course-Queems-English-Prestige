@props([
'questionId' => 1,
'number' => 'Question 1',
'type' => 'multiple_choice',
'typeLabel' => 'Question',
'question' => 'Question text',
'options' => [],
'placeholder' => 'Type your answer here...',
])

<div class="overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-slate-400">
            {{ $number }}
        </p>

        <p class="text-[12px] font-bold uppercase tracking-[0.16em] text-[var(--color-brand-blue)]">
            {{ $typeLabel }}
        </p>
    </div>

    <div class="px-6 py-7">
        <div class="rich-text-content max-w-4xl text-slate-900">
            {!! $question !!}
        </div>

        @error('answers.' . $questionId)
        <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600">
            {{ $message }}
        </p>
        @enderror

        @error('uploads.' . $questionId)
        <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600">
            {{ $message }}
        </p>
        @enderror

        @if ($type === 'multiple_choice')
        <div class="mt-8 space-y-4">
            @foreach ($options as $option)
            <label class="flex cursor-pointer items-start gap-4 rounded-2xl border border-slate-200 bg-white px-5 py-5 transition hover:border-slate-300 hover:bg-slate-50">
                <input
                    type="radio"
                    name="answers[{{ $questionId }}]"
                    value="{{ $option->id }}"
                    @checked((string) old('answers.' . $questionId)===(string) $option->id)
                class="mt-1 h-5 w-5 border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">

                <span class="text-lg font-medium leading-8 text-slate-700">
                    <span class="mr-2 font-extrabold text-[var(--color-brand-blue)]">
                        {{ $option->option_label }}.
                    </span>
                    {{ $option->option_text }}
                </span>
            </label>
            @endforeach
        </div>
        @elseif ($type === 'essay')
        <div class="mt-8">
            <textarea
                name="answers[{{ $questionId }}]"
                rows="7"
                placeholder="{{ $placeholder }}"
                class="w-full resize-y rounded-2xl border border-slate-200 bg-white px-4 py-4 text-base leading-7 text-slate-900 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('answers.' . $questionId) }}</textarea>
        </div>
        @elseif ($type === 'upload')
        <div class="mt-8 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-5">
            <label class="block text-sm font-extrabold text-slate-900">
                Upload your answer file
            </label>

            <input
                type="file"
                name="uploads[{{ $questionId }}]"
                class="mt-3 block w-full text-sm font-medium text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-[var(--color-brand-blue)] file:px-4 file:py-2.5 file:text-sm file:font-bold file:text-white hover:file:opacity-90">

            <p class="mt-3 text-xs leading-5 text-slate-500">
                Upload a document, image, audio, or video file according to the instruction.
            </p>
        </div>
        @else
        <div class="mt-8">
            <input
                type="text"
                name="answers[{{ $questionId }}]"
                value="{{ old('answers.' . $questionId) }}"
                placeholder="{{ $placeholder }}"
                class="h-14 w-full rounded-2xl border border-slate-200 bg-white px-4 text-base text-slate-900 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        @endif
    </div>
</div>