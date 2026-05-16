<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentCourseEnrollment;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();

        $profile = $user->studentProfile()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        $latestEnrollment = StudentCourseEnrollment::query()
            ->with('courseLevel.courseProgram')
            ->where('student_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->latest('enrolled_at')
            ->latest()
            ->first();

        return view('pages.student.profile', [
            'user' => $user,
            'profile' => $profile,
            'latestEnrollment' => $latestEnrollment,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $profile = $user->studentProfile()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        $section = $request->input('_section', 'personal');

        if ($section === 'photo') {
            return $this->updatePhoto($request, $profile);
        }

        if ($section === 'additional') {
            return $this->updateAdditionalInformation($request, $profile);
        }

        return $this->updatePersonalInformation($request, $profile);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors([
                    'current_password' => 'Current password is incorrect.',
                ])
                ->withInput();
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('student.profile')
            ->with('success', 'Password has been updated successfully.');
    }

    public function destroyPhoto(): RedirectResponse
    {
        $user = auth()->user();

        $profile = $user->studentProfile()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        if ($profile->photo) {
            Storage::disk('public')->delete($profile->photo);
        }

        $profile->update([
            'photo' => null,
        ]);

        return redirect()
            ->route('student.profile')
            ->with('success', 'Profile photo has been removed successfully.');
    }

    private function updatePersonalInformation(Request $request, StudentProfile $profile): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        auth()->user()->update([
            'name' => $validated['name'],
        ]);

        $profile->update([
            'whatsapp' => $validated['whatsapp'] ?? null,
            'birth_place' => $validated['birth_place'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return redirect()
            ->route('student.profile')
            ->with('success', 'Personal information has been updated successfully.');
    }

    private function updateAdditionalInformation(Request $request, StudentProfile $profile): RedirectResponse
    {
        $validated = $request->validate([
            'profession' => ['nullable', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'social_media' => ['nullable', 'string', 'max:255'],
        ]);

        $profile->update([
            'profession' => $validated['profession'] ?? null,
            'institution' => $validated['institution'] ?? null,
            'social_media' => $validated['social_media'] ?? null,
        ]);

        return redirect()
            ->route('student.profile')
            ->with('success', 'Additional information has been updated successfully.');
    }

    private function updatePhoto(Request $request, StudentProfile $profile): RedirectResponse
    {
        $validated = $request->validate([
            'photo' => ['required', 'image', 'max:2048'],
        ]);

        if ($profile->photo) {
            Storage::disk('public')->delete($profile->photo);
        }

        $profile->update([
            'photo' => $validated['photo']->store('student-profiles', 'public'),
        ]);

        return redirect()
            ->route('student.profile')
            ->with('success', 'Profile photo has been updated successfully.');
    }
}
