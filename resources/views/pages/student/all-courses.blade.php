@extends('layouts.student')

@section('content')
@php
$courseItems = [
[
'title' => 'Academic Writing',
'mode' => 'Online',
'level' => 'Advanced',
'price' => 'Rp. 100.000',
'description' => 'Refine your ability to construct complex arguments and professional research papers...',
'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'IELTS Breakthrough',
'mode' => 'Offline',
'level' => 'Intermediate',
'price' => 'Rp. 100.000',
'description' => 'Strategic preparation focused on achieving Band 8+ through intensive practice and expe...',
'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Executive',
'mode' => 'Offline',
'level' => 'Advanced',
'price' => 'Rp. 100.000',
'description' => 'Master the nuances of professional English for boardrooms, high-stakes negotiations, and...',
'image' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'IELTS Breakthrough',
'mode' => 'Offline',
'level' => 'Intermediate',
'price' => 'Rp. 100.000',
'description' => 'Strategic preparation focused on achieving Band 8+ through intensive practice and expe...',
'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Speaking Confidence',
'mode' => 'Offline',
'level' => 'Beginner',
'price' => 'Rp. 100.000',
'description' => 'Break language barriers and gain the courage to speak fluently in any social or business...',
'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Grammar Essentials',
'mode' => 'Online',
'level' => 'Intermediate',
'price' => 'Rp. 100.000',
'description' => 'Deep dive into the structural foundations of English for precise and sophisticated languag...',
'image' => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Public Speaking Pro',
'mode' => 'Online',
'level' => 'Advanced',
'price' => 'Rp. 100.000',
'description' => 'Persuade and inspire. Techniques for impactful presentations, storytelling, and charismatic...',
'image' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
[
'title' => 'Grammar Essentials',
'mode' => 'Online',
'level' => 'Intermediate',
'price' => 'Rp. 100.000',
'description' => 'Deep dive into the structural foundations of English for precise and sophisticated languag...',
'image' => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?q=80&w=1200&auto=format&fit=crop',
'href' => route('courses.show'),
],
];
@endphp

<section class="-mx-4 -mt-6 overflow-hidden bg-white lg:-mx-8">
    @include('partials.student.all-courses.hero')
    @include('partials.student.all-courses.filters')
    @include('partials.student.all-courses.grid')
</section>
@endsection