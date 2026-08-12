<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolSettingsController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\AnekdotController;
use App\Http\Controllers\TagihanSppController;
use App\Http\Controllers\PembayaranSppController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ERaporController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
})->name('landing');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // School Profile & QRIS Settings
    Route::get('/settings/school', [SchoolSettingsController::class, 'edit'])->name('settings.school.edit');
    Route::post('/settings/school', [SchoolSettingsController::class, 'update'])->name('settings.school.update');

    // Master Data
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');

    Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');

    Route::get('/rombel', [RombelController::class, 'index'])->name('rombel.index');
    Route::post('/rombel', [RombelController::class, 'store'])->name('rombel.store');

    // Presensi Module (Dual Mode: Guru Morning Entry & Siswa Self-Attendance)
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/guru', [PresensiController::class, 'storeGuru'])->name('presensi.guru.store');
    Route::get('/presensi/mandiri', [PresensiController::class, 'showMandiri'])->name('presensi.mandiri');
    Route::post('/presensi/mandiri', [PresensiController::class, 'storeMandiri'])->name('presensi.mandiri.store');

    // Anekdot Timeline
    Route::get('/anekdot', [AnekdotController::class, 'index'])->name('anekdot.index');
    Route::post('/anekdot', [AnekdotController::class, 'store'])->name('anekdot.store');

    // SPP Invoicing & Manual QRIS Payment Proof Workflow
    Route::get('/spp/tagihan', [TagihanSppController::class, 'index'])->name('spp.index');
    Route::post('/spp/generate', [TagihanSppController::class, 'generate'])->name('spp.generate');
    Route::post('/spp/upload-bukti/{tagihan}', [PembayaranSppController::class, 'uploadBukti'])->name('spp.upload-bukti');
    Route::get('/spp/verifikasi', [PembayaranSppController::class, 'verifikasiQueue'])->name('spp.verifikasi.queue');
    Route::post('/spp/verifikasi/{pembayaran}', [PembayaranSppController::class, 'verifikasiStore'])->name('spp.verifikasi.store');

    // Financial Assistant (BendaharaKu / Talangan & LPJ BOSP)
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::post('/expenses/{expense}/status', [ExpenseController::class, 'updateStatus'])->name('expenses.update-status');
    Route::get('/expenses/export-pdf', [ExpenseController::class, 'exportPdf'])->name('expenses.export-pdf');

    // E-Rapor Engine & PDF Download
    Route::get('/erapor', [ERaporController::class, 'index'])->name('erapor.index');
    Route::post('/erapor/assessment', [ERaporController::class, 'storeAssessment'])->name('erapor.assessment.store');
    Route::get('/erapor/pdf/{siswa}', [ERaporController::class, 'exportPdf'])->name('erapor.pdf');
});
