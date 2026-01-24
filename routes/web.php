<?php

use App\Http\Controllers\CertificatePublicController;
use App\Filament\Admin\Pages\Auth\PasswordReset;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class ,'home' ])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/certificates/{certificate:serial}', [CertificatePublicController::class, 'show'])->name('certificates.show');
//Route::get('/admin/password-reset', PasswordReset::class)->name('filament.password-reset');

require __DIR__.'/auth.php';
