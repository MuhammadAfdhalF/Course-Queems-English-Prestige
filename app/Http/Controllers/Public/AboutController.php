<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Mentor;
use App\Models\ProfileVideo;
use App\Models\VisionsMission;
use App\Models\WhyChooseUs;

class AboutController extends Controller
{
    public function index()
    {
        $aboutUs = AboutUs::query()
            ->where('is_active', true)
            ->latest()
            ->first();

        $visionMission = VisionsMission::query()
            ->where('is_active', true)
            ->with('missionItems')
            ->latest()
            ->first();

        $profileVideo = ProfileVideo::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest()
            ->first();

        $mentors = Mentor::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $whyChooseUsItems = WhyChooseUs::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit(6)
            ->get();

        return view('pages.public.about', compact(
            'aboutUs',
            'visionMission',
            'profileVideo',
            'mentors',
            'whyChooseUsItems'
        ));
    }
}
