<div class="space-y-5">
    @foreach ($questions as $index => $question)
        @php
            $delayClass = match ($index) {
                1 => 'reveal-delay-1',
                2 => 'reveal-delay-2',
                default => '',
            };
        @endphp

        <div class="reveal {{ $delayClass }}">
            <x-student.final-exam-question-card
                :question-id="$question['id']"
                :number="$question['number']"
                :category="$question['category']"
                :type="$question['type']"
                :question="$question['question']"
                :options="$question['options'] ?? []"
                :sentence="$question['sentence'] ?? ''"
                :placeholder="$question['placeholder'] ?? 'Type your answer here'"
                :note="$question['note'] ?? ''"
                :audio-progress="$question['audioProgress'] ?? 0"
                :audio-current="$question['audioCurrent'] ?? '00:00'"
                :audio-total="$question['audioTotal'] ?? '00:00'"
            />
        </div>
    @endforeach
</div>