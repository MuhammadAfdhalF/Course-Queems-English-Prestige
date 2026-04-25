@php
    $newsItems = [
        [
            'category' => 'Education Tips',
            'date' => 'Oct 18, 2023',
            'title' => 'Top 5 Universities in the UK for International Students',
            'excerpt' => 'A comprehensive guide to elite institutions and their specific language requirements for 2024 admissions.',
            'image' => 'https://placehold.co/800x500/9ed4f6/1e293b?text=University+Guide',
        ],
        [
            'category' => 'Events',
            'date' => 'Oct 12, 2023',
            'title' => 'Upcoming Workshop: Advanced Academic Writing',
            'excerpt' => 'Refine your thesis construction and critical analysis skills with our visiting Oxford professors.',
            'image' => 'https://placehold.co/800x500/776046/ffffff?text=Academic+Workshop',
        ],
        [
            'category' => 'Announcements',
            'date' => 'Oct 05, 2023',
            'title' => 'Winter Term Holiday Schedule & Deadlines',
            'excerpt' => 'Key information regarding center holiday closures and upcoming application submission deadlines.',
            'image' => 'https://placehold.co/800x500/cb9a4d/ffffff?text=Holiday+Schedule',
        ],
        [
            'category' => 'Education Tips',
            'date' => 'Oct 18, 2023',
            'title' => 'Top 5 Universities in the UK for International Students',
            'excerpt' => 'A comprehensive guide to elite institutions and their specific language requirements for 2024 admissions.',
            'image' => 'https://placehold.co/800x500/9ed4f6/1e293b?text=University+Guide',
        ],
        [
            'category' => 'Announcements',
            'date' => 'Oct 05, 2023',
            'title' => 'Winter Term Holiday Schedule & Deadlines',
            'excerpt' => 'Key information regarding center holiday closures and upcoming application submission deadlines.',
            'image' => 'https://placehold.co/800x500/cb9a4d/ffffff?text=Holiday+Schedule',
        ],
        [
            'category' => 'Events',
            'date' => 'Sep 20, 2023',
            'title' => 'Global Alumni Networking Night 2023',
            'excerpt' => 'Join former students who now attend Ivy League and Russell Group universities for a mentorship evening.',
            'image' => 'https://placehold.co/800x500/907e6d/ffffff?text=Alumni+Night',
        ],
        [
            'category' => 'Announcements',
            'date' => 'Sep 15, 2023',
            'title' => 'New Smart Learning Hub Opening Downtown',
            'excerpt' => 'We are expanding our premium facilities to include a state-of-the-art computer-based testing lab.',
            'image' => 'https://placehold.co/800x500/4f8677/ffffff?text=Learning+Hub',
        ],
        [
            'category' => 'Events',
            'date' => 'Sep 20, 2023',
            'title' => 'Global Alumni Networking Night 2023',
            'excerpt' => 'Join former students who now attend Ivy League and Russell Group universities for a mentorship evening.',
            'image' => 'https://placehold.co/800x500/907e6d/ffffff?text=Alumni+Night',
        ],
    ];
@endphp

<section class="bg-[#f8f8f6]">
    <div class="mx-auto max-w-7xl px-4 py-10 lg:px-8 lg:py-12">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($newsItems as $index => $item)
                @php
                    $delayClass = match ($index % 4) {
                        1 => 'reveal-delay-1',
                        2 => 'reveal-delay-2',
                        3 => 'reveal-delay-3',
                        default => '',
                    };
                @endphp

                <div class="reveal {{ $delayClass }}">
                    <x-public.news-card
                        :title="$item['title']"
                        :category="$item['category']"
                        :date="$item['date']"
                        :excerpt="$item['excerpt']"
                        :image="$item['image']"
                        href="#" />
                </div>
            @endforeach
        </div>
    </div>
</section>