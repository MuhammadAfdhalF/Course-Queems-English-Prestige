@props([
'questionId' => 1,
'number' => 'Question 1',
'type' => 'multiple_choice',
'typeLabel' => 'Multiple Choice',
'question' => 'Question text',
'options' => [],
'placeholder' => 'Type your answer here...',
])

<div class="rounded-[22px] border border-slate-200 bg-white px-6 py-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
        <p class="text-xl font-bold text-[var(--color-brand-blue)]">
            {{ $number }}
        </p>

        <p class="text-sm font-medium text-slate-400">
            {{ $typeLabel }}
        </p>
    </div>

    <div class="mt-5">
        <h3 class="max-w-3xl text-[18px] font-medium leading-9 text-slate-900 lg:text-[20px]">
            {{ $question }}
        </h3>
    </div>

    @if($type === 'multiple_choice')
    <div class="mt-6 space-y-3">
        @foreach ($options as $index => $option)
        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-slate-200 bg-white px-4 py-4 transition hover:border-slate-300 hover:bg-slate-50">
            <input
                type="radio"
                name="question_{{ $questionId }}"
                :value="'{{ $option }}'"
                x-model="answers[{{ $questionId }}]"
                class="h-5 w-5 border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">

            <span class="text-lg font-medium text-slate-700">
                {{ $option }}
            </span>
        </label>
        @endforeach
    </div>
    @else
    <div class="mt-6">
        <input
            type="text"
            x-model="answers[{{ $questionId }}]"
            placeholder="{{ $placeholder }}"
            class="h-14 w-full rounded-2xl border border-slate-200 bg-white px-4 text-base text-slate-900 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100">
    </div>
    @endif
</div>