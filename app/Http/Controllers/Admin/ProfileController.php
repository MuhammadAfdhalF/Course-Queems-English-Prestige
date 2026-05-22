<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('pages.admin.profile.edit', [
            'admin' => auth()->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $admin->update([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile information has been updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $admin = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Password has been updated.');
    }
}
