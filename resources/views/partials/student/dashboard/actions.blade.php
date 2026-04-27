<div class="reveal reveal-delay-2 flex flex-col items-center justify-center gap-4 pt-2 sm:flex-row">
    <a href="{{ route('student.my-courses') }}">
        <x-ui.button class="min-w-[220px] px-6 py-3">
            Go to My Courses
        </x-ui.button>
    </a>

    <a href="{{ route('student.all-courses') }}">
        <x-ui.button variant="outline" class="min-w-[220px] px-6 py-3">
            Browse All Courses
        </x-ui.button>
    </a>
</div>