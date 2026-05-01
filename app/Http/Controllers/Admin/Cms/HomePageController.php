<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\HeroSection;
use App\Models\Testimonial;

class HomePageController extends Controller
{
    public function index()
    {
        $heroSectionsCount = HeroSection::count();
        $faqsCount = Faq::count();
        $featuredTestimonialsCount = Testimonial::where('is_featured', true)->count();

        return view('pages.admin.cms.home.index', compact(
            'heroSectionsCount',
            'faqsCount',
            'featuredTestimonialsCount'
        ));
    }
}