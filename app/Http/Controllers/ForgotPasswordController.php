<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function forgot_password()
    {
        return view('apps.authentication.forgot-password');
    }

    public function forgot_password_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ],[
            'email.required' => "Masukkan Email Anda dengan Benar",
            'email.email' => "Masukkan Email Anda dengan Benar",
            'email.exists' => "Email Anda Tidak Ditemukan",
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status), 'forgotMessage' => 'Reset Link Berhasil Dikirim ke Email'])
                : back()->withErrors(['email' => __($status)]);
    }
}
