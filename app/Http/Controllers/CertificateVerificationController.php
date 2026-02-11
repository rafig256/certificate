<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function showForm()
    {
        return view('verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $certificate = Certificate::where('serial', $request->code)->first();

        if (!$certificate) {
            return back()->withErrors([
                'code' => 'گواهینامه‌ای با این کد یافت نشد.'
            ]);
        }

        return redirect()->route('certificates.show', $certificate->serial);
    }
}
