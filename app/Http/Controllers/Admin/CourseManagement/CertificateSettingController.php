<?php

namespace App\Http\Controllers\Admin\CourseManagement;

use App\Http\Controllers\Controller;
use App\Models\CertificateSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CertificateSettingController extends Controller
{
    public function edit(): View
    {
        $setting = CertificateSetting::current();

        return view('pages.admin.course-management.certificate-settings.edit', [
            'setting' => $setting,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = CertificateSetting::current();

        $validated = $request->validate([
            'signer_name' => ['nullable', 'string', 'max:255'],
            'signer_title' => ['nullable', 'string', 'max:255'],
            'signature_image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('signature_image')) {
            if ($setting->signature_image) {
                Storage::disk('public')->delete($setting->signature_image);
            }

            $validated['signature_image'] = $request
                ->file('signature_image')
                ->store('certificate-signatures', 'public');
        }

        $setting->update([
            'signer_name' => $validated['signer_name'] ?? null,
            'signer_title' => $validated['signer_title'] ?? null,
            'signature_image' => $validated['signature_image'] ?? $setting->signature_image,
        ]);

        return redirect()
            ->route('admin.course-management.certificate-settings.edit')
            ->with('success', 'Certificate settings have been updated successfully.');
    }
}
