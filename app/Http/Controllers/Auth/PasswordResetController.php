<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function showForm(){
        return view('auth.password-reset');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'national_code' => ['required'],
        ]);

        $user = User::where('national_code', $request->national_code)->first();

        if (! $user) {
            return back()->withErrors([
                'national_code' => 'کاربری با این کد ملی یافت نشد.',
            ]);
        }

        if (! $user->email) {
            return back()->withErrors([
                'national_code' => 'برای این حساب ایمیلی ثبت نشده. با مدیریت تماس بگیرید.',
            ]);
        }

        Password::sendResetLink(['email' => $user->email]);

        return back()->with('status', 'لینک بازیابی رمز عبور به ایمیل ارسال شد.');
    }
}
