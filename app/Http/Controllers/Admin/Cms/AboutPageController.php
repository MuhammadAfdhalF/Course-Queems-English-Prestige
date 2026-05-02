<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Mentor;
use App\Models\ProfileVideo;
use App\Models\VisionsMission;
use App\Models\WhyChooseUs;

class AboutPageController extends Controller
{
    public function index()
    {
        $aboutUsCount = AboutUs::count();
        $profileVideosCount = ProfileVideo::count();
        $whyChooseUsCount = WhyChooseUs::count();
        $visionsMissionsCount = VisionsMission::count();
        $mentorsCount = Mentor::count();

        return view('pages.admin.cms.about.index', compact(
            'aboutUsCount',
            'profileVideosCount',
            'whyChooseUsCount',
            'visionsMissionsCount',
            'mentorsCount'
        ));
    }
}