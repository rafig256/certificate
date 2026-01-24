<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CertificatePublicController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class ,'home' ])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/certificates/{certificate:serial}', [CertificatePublicController::class, 'show'])->name('certificates.show');

//Password Reset
Route::get('/password-reset', [PasswordResetController::class, 'showForm'])->name('password.request');
Route::post('/password-reset', [PasswordResetController::class, 'submit'])->name('password.email');

require __DIR__.'/auth.php';
