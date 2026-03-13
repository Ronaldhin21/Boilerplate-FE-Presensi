<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SiswaAuthController;
use App\Http\Controllers\SiswaRegistrationController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\DataSiswaController;

Route::redirect('/', '/login');

// Siswa Login routes (public)
Route::get('/login', [SiswaAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [SiswaAuthController::class, 'login']);
Route::post('/logout', [SiswaAuthController::class, 'logout'])->name('logout');

// Siswa Register routes (public)
Route::get('/register', [SiswaRegistrationController::class, 'showBiodataForm'])->name('siswa.register.biodata');
Route::post('/register/biodata', [SiswaRegistrationController::class, 'storeBiodata'])->name('siswa.register.biodata.store');
Route::get('/register/account', [SiswaRegistrationController::class, 'showAccountForm'])->name('siswa.register.account');
Route::post('/register/account', [SiswaRegistrationController::class, 'storeAccount'])->name('siswa.register.account.store');
Route::get('/register/verify', [SiswaRegistrationController::class, 'showVerifyForm'])->name('siswa.register.verify');
Route::post('/register/verify', [SiswaRegistrationController::class, 'verifyCode'])->name('siswa.register.verify.store');
Route::post('/register/verify/resend', [SiswaRegistrationController::class, 'resendCode'])->name('siswa.register.verify.resend');

// Admin Login routes (public)
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::redirect('/admin', '/admin/login');

// Admin Protected routes (require admin login)
Route::middleware(['admin.auth'])->prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/laporan', 'laporan.index')->name('admin.laporan');
    Route::view('/siswa', 'admin.siswa')->name('admin.siswa');

    // Data Siswa Management
    Route::get('/data-siswa', [DataSiswaController::class, 'index'])->name('admin.data-siswa');
    Route::post('/data-siswa/import', [DataSiswaController::class, 'import'])->name('admin.data-siswa.import');
    Route::put('/data-siswa/{id}', [DataSiswaController::class, 'update'])->name('admin.data-siswa.update');
    Route::delete('/data-siswa/{id}', [DataSiswaController::class, 'destroy'])->name('admin.data-siswa.destroy');
    Route::delete('/data-siswa/bulk/{kelas}/{rombel}', [DataSiswaController::class, 'destroyByKelasRombel'])->name('admin.data-siswa.destroy-bulk');
    Route::get('/data-siswa/template', [DataSiswaController::class, 'downloadTemplate'])->name('admin.data-siswa.template');

    // QR Code Management
    Route::post('/qr/generate', [QrCodeController::class, 'generate'])->name('admin.qr.generate');
    Route::get('/qr/{id}', [QrCodeController::class, 'show'])->name('admin.qr.show');
    Route::put('/qr/{id}/deactivate', [QrCodeController::class, 'deactivate'])->name('admin.qr.deactivate');
});

// Siswa Protected routes (require siswa login)
Route::middleware(['siswa.auth'])->group(function () {
    Route::view('/dashboard', 'dashboard.index')->name('home');
    Route::view('/presensi', 'presensi.index')->name('presensi.index');
    Route::view('/presensi/create', 'presensi.create')->name('presensi.create');
    Route::view('/laporan', 'laporan.index')->name('laporan.index');
    Route::view('/profil', 'profile.index')->name('profile.index');
    Route::view('/scan-qr', 'presensi.scan')->name('scan.qr');

    // QR Code Scanning
    Route::post('/qr/scan', [QrCodeController::class, 'scan'])->name('qr.scan');
});

require __DIR__.'/settings.php';

