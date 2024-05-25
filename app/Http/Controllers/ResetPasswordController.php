<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function reset_password($token){
        return view('apps.authentication.reset-password', [
            'token' => $token,
        ]);
    }
   
    public function reset_password_submit(Request $request){

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
        ],[
            'token.required' => "Token diperlukan atau Token expired",
            'email.required' => "Email Diperlukan",
            'email.email' => "Email Harus Valid",
            'password.required' => "Password dibutuhkan",
            'password.min' => "Password minimal 8 karakter",
            'password.confirmed' => "Password harus sama dengan kolom konfirmasi password",
            'password_confirmation.required' => "Konfirmasi password dibutuhkan",
            'password_confirmation.same' => "Konfirmasi password harus sama dengan kolom password",
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
    }
}
