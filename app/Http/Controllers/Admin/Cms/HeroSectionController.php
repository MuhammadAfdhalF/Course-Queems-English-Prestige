<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;

class HeroSectionController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::query()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.admin.cms.home.hero-sections.index', compact('heroSections'));
    }
}