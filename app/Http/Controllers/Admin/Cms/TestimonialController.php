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
        $visibility = $request->query('visibility', 'all');

        if (! in_array($type, ['all', 'course', 'company'], true)) {
            $type = 'all';
        }

        if (! in_array($visibility, ['all', 'visible', 'hidden'], true)) {
            $visibility = 'all';
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
            ->when($visibility === 'visible', function ($query) {
                $query->where('is_active', true)
                    ->where('is_featured', true);
            })
            ->when($visibility === 'hidden', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('is_active', false)
                        ->orWhere('is_featured', false);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.cms.testimonials.index', [
            'testimonials' => $testimonials,
            'type' => $type,
            'visibility' => $visibility,
            'totalTestimonials' => (clone $baseQuery)->count(),
            'visibleTestimonials' => (clone $baseQuery)
                ->where('is_active', true)
                ->where('is_featured', true)
                ->count(),
            'hiddenTestimonials' => (clone $baseQuery)
                ->where(function ($query) {
                    $query->where('is_active', false)
                        ->orWhere('is_featured', false);
                })
                ->count(),
            'courseTestimonials' => (clone $baseQuery)->where('type', 'course')->count(),
            'companyTestimonials' => (clone $baseQuery)->where('type', 'company')->count(),
        ]);
    }

    public function showOnHome(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'is_active' => true,
            'is_featured' => true,
        ]);

        return redirect()
            ->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial is now visible on homepage.');
    }

    public function hideFromHome(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'is_active' => false,
            'is_featured' => false,
        ]);

        return redirect()
            ->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial has been hidden from homepage.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()
            ->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial has been deleted successfully.');
    }
}
