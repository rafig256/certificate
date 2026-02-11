<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function showForm()
    {
        return view('verify');
    }

    public function verify()
    {
        
    }
}
