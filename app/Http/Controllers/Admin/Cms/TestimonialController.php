<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type', 'all');
        $status = $request->query('status', 'all');

        if (! in_array($type, ['all', 'course', 'company'], true)) {
            $type = 'all';
        }

        if (! in_array($status, ['all', 'awaiting', 'published', 'featured'], true)) {
            $status = 'all';
        }

        $baseQuery = Testimonial::query();

        $testimonials = Testimonial::query()
            ->with([
                'student',
                'courseLevel.courseProgram',
            ])
            ->when($type !== 'all', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($status === 'awaiting', function ($query) {
                $query->where('is_active', false);
            })
            ->when($status === 'published', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'featured', function ($query) {
                $query->where('is_featured', true);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.cms.testimonials.index', [
            'testimonials' => $testimonials,
            'type' => $type,
            'status' => $status,
            'totalTestimonials' => (clone $baseQuery)->count(),
            'awaitingTestimonials' => (clone $baseQuery)->where('is_active', false)->count(),
            'publishedTestimonials' => (clone $baseQuery)->where('is_active', true)->count(),
            'featuredTestimonials' => (clone $baseQuery)->where('is_featured', true)->count(),
            'courseTestimonials' => (clone $baseQuery)->where('type', 'course')->count(),
            'companyTestimonials' => (clone $baseQuery)->where('type', 'company')->count(),
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
