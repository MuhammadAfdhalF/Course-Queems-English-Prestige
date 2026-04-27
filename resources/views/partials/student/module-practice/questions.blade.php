<div class="space-y-5">
    @foreach ($questions as $index => $question)
        @php
            $delayClass = match ($index) {
                1 => 'reveal-delay-1',
                2 => 'reveal-delay-2',
                3 => 'reveal-delay-3',
                4 => 'reveal-delay-4',
                default => '',
            };
        @endphp

        <div class="reveal {{ $delayClass }}">
            <x-student.practice-question-card
                :question-id="$question['id']"
                :number="$question['number']"
                :type="$question['type']"
                :type-label="$question['typeLabel']"
                :question="$question['question']"
                :options="$question['options'] ?? []"
                :placeholder="$question['placeholder'] ?? 'Type your answer here...'"
            />
        </div>
    @endforeach
</div>