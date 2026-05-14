<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::query()
            ->with([
                'student',
                'courseLevel.courseProgram',
            ])
            ->latest()
            ->get();

        return view('pages.admin.cms.testimonials.index', [
            'testimonials' => $testimonials,
        ]);
    }

    public function publish(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial has been published successfully.');
    }

    public function unpublish(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'is_active' => false,
            'is_featured' => false,
        ]);

        return redirect()
            ->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial has been unpublished successfully.');
    }

    public function feature(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'is_active' => true,
            'is_featured' => true,
        ]);

        return redirect()
            ->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial has been featured successfully.');
    }

    public function unfeature(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'is_featured' => false,
        ]);

        return redirect()
            ->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial has been removed from featured list.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()
            ->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial has been deleted successfully.');
    }
}
