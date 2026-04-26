@props([
'questionId' => 1,
'number' => 'Question 1 of 20',
'category' => 'Reading Comprehension',
'type' => 'multiple_choice', // multiple_choice | fill_blank | listening_choice
'question' => 'Question text',
'options' => [],
'sentence' => '',
'placeholder' => 'type your answer here',
'note' => '',
'audioProgress' => 0,
'audioTime' => '',
])

<div class="overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-slate-400">
            {{ $number }}
        </p>

        <p class="text-[12px] font-bold uppercase tracking-[0.16em] text-[var(--color-brand-blue)]">
            {{ $category }}
        </p>
    </div>

    <div class="px-6 py-7">
        @if($type === 'listening_choice')
        <div class="mb-8 rounded-2xl bg-slate-50 px-5 py-4">
            <div class="flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-[var(--color-brand-blue)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 18V6a4 4 0 10-8 0v6a4 4 0 008 0zm0 0a4 4 0 008 0V6a4 4 0 10-8 0" />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-200">
                        <div class="h-full rounded-full bg-[var(--color-brand-blue)]" style="width: {{ $audioProgress }}%"></div>
                    </div>
                </div>

                <p class="shrink-0 text-sm font-semibold text-slate-400">
                    {{ $audioTime }}
                </p>
            </div>
        </div>
        @endif

        <h3 class="max-w-4xl text-[22px] font-semibold leading-10 text-slate-900">
            {{ $question }}
        </h3>

        @if($type === 'multiple_choice' || $type === 'listening_choice')
        <div class="mt-8 space-y-4">
            @foreach ($options as $option)
            <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-slate-200 bg-white px-5 py-5 transition hover:border-slate-300 hover:bg-slate-50">
                <input
                    type="radio"
                    name="final_exam_question_{{ $questionId }}"
                    :value="'{{ $option }}'"
                    x-model="answers[{{ $questionId }}]"
                    class="h-5 w-5 border-slate-300 text-[var(--color-brand-blue)] focus:ring-[var(--color-brand-blue)]">

                <span class="text-lg font-medium text-slate-700">
                    {{ $option }}
                </span>
            </label>
            @endforeach
        </div>
        @elseif($type === 'fill_blank')
        <div class="mt-8 rounded-2xl bg-slate-50 px-5 py-5">
            <p class="text-[20px] leading-10 text-slate-700">
                {!! str_replace(
                '_____',
                '<input type="text" x-model="answers[' . $questionId . ']" placeholder="' . e($placeholder) . '" class="mx-2 inline-block h-12 w-[220px] rounded-xl border border-slate-200 bg-white px-4 text-base text-slate-900 placeholder:text-slate-400 focus:border-[var(--color-brand-blue)] focus:outline-none focus:ring-2 focus:ring-blue-100 align-middle">',
                e($sentence)
                ) !!}
            </p>
        </div>

        @if($note)
        <p class="mt-5 text-sm font-medium text-slate-400">
            {{ $note }}
        </p>
        @endif
        @endif
    </div>
</div>