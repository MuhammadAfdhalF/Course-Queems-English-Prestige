<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('pages.auth.forgot-password', [
            'title' => 'Forgot Password - Queens English Prestige',
        ]);
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $status = Password::broker()->sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_THROTTLED) {
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Terlalu banyak permintaan reset password. Silakan coba kembali beberapa saat lagi.');
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send password reset email', [
                'email' => $request->input('email'),
                'exception' => get_class($e),
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with(
            'status',
            'Jika email tersebut terdaftar, link reset password akan dikirim.'
        );
    }
}
