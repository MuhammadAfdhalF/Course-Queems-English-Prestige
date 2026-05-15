<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use App\Models\CourseProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CertificateTemplateController extends Controller
{
    public function index(): View
    {
        $templates = CertificateTemplate::query()
            ->with(['courseProgram'])
            ->withCount('certificates')
            ->latest()
            ->get();

        $coursePrograms = CourseProgram::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.admin.course-management.certificate-templates.index', [
            'templates' => $templates,
            'coursePrograms' => $coursePrograms,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTemplate($request);

        if ($request->hasFile('background_image')) {
            $validated['background_image'] = $request
                ->file('background_image')
                ->store('certificate-templates', 'public');
        }

        $validated['course_program_id'] = $validated['course_program_id'] ?? null;
        $validated['is_default'] = $request->boolean('is_default');
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_default']) {
            $this->unsetOtherDefaults($validated['course_program_id']);
        }

        CertificateTemplate::create($validated);

        return redirect()
            ->route('admin.course-management.certificate-templates.index')
            ->with('success', 'Certificate template has been created successfully.');
    }

    public function update(Request $request, CertificateTemplate $certificateTemplate): RedirectResponse
    {
        $validated = $this->validateTemplate($request);

        if ($request->hasFile('background_image')) {
            if ($certificateTemplate->background_image) {
                Storage::disk('public')->delete($certificateTemplate->background_image);
            }

            $validated['background_image'] = $request
                ->file('background_image')
                ->store('certificate-templates', 'public');
        }

        $validated['course_program_id'] = $validated['course_program_id'] ?? null;
        $validated['is_default'] = $request->boolean('is_default');
        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_default']) {
            $this->unsetOtherDefaults(
                $validated['course_program_id'],
                $certificateTemplate->id
            );
        }

        $certificateTemplate->update($validated);

        return redirect()
            ->route('admin.course-management.certificate-templates.index')
            ->with('success', 'Certificate template has been updated successfully.');
    }

    public function destroy(CertificateTemplate $certificateTemplate): RedirectResponse
    {
        if ($certificateTemplate->certificates()->exists()) {
            return redirect()
                ->route('admin.course-management.certificate-templates.index')
                ->with('error', 'This template is already used by certificates. Please deactivate it instead of deleting it.');
        }

        if ($certificateTemplate->background_image) {
            Storage::disk('public')->delete($certificateTemplate->background_image);
        }

        $certificateTemplate->delete();

        return redirect()
            ->route('admin.course-management.certificate-templates.index')
            ->with('success', 'Certificate template has been deleted successfully.');
    }

    private function validateTemplate(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'course_program_id' => ['nullable', 'exists:course_programs,id'],
            'background_image' => ['nullable', 'image', 'max:4096'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function unsetOtherDefaults(?int $courseProgramId, ?int $exceptTemplateId = null): void
    {
        CertificateTemplate::query()
            ->when(
                $courseProgramId,
                fn($query) => $query->where('course_program_id', $courseProgramId),
                fn($query) => $query->whereNull('course_program_id')
            )
            ->when(
                $exceptTemplateId,
                fn($query) => $query->where('id', '!=', $exceptTemplateId)
            )
            ->update([
                'is_default' => false,
            ]);
    }
}
