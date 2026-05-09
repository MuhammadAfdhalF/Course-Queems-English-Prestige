<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Contact;
use App\Models\CourseLevel;
use App\Models\Faq;
use App\Models\FreeTestCategory;
use App\Models\HeroSection;
use App\Models\InformationPost;
use App\Models\WhyChooseUs;

class HomeController extends Controller
{
    public function index()
    {
        $heroSection = HeroSection::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest()
            ->first();

        $aboutUs = AboutUs::query()
            ->where('is_active', true)
            ->latest()
            ->first();

        $whyChooseUsItems = WhyChooseUs::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest()
            ->limit(6)
            ->get();

        $featuredCourses = CourseLevel::query()
            ->where('is_active', true)
            ->with('courseProgram')
            ->orderBy('sort_order')
            ->latest()
            ->limit(4)
            ->get();

        $freeTestCategories = FreeTestCategory::query()
            ->where('is_active', true)
            ->withCount([
                'freeTests' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->orderBy('sort_order')
            ->latest()
            ->limit(4)
            ->get();

        $latestPosts = InformationPost::query()
            ->where('is_published', true)
            ->where(function ($query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByRaw('published_at IS NULL')
            ->orderByDesc('published_at')
            ->latest()
            ->limit(4)
            ->get();

        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest()
            ->limit(4)
            ->get();

        $contact = Contact::query()
            ->where('is_active', true)
            ->with([
                'socialLinks' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('sort_order');
                },
            ])
            ->latest()
            ->first();

        return view('pages.public.home', compact(
            'heroSection',
            'aboutUs',
            'whyChooseUsItems',
            'featuredCourses',
            'freeTestCategories',
            'latestPosts',
            'faqs',
            'contact'
        ));
    }
}
