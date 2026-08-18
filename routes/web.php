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
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ERaporController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Superadmin\SubscriptionPlanController;
use App\Http\Controllers\Superadmin\TenantSubscriptionController;
use App\Http\Controllers\Superadmin\PageManagementController;
use App\Http\Controllers\Superadmin\WahaSettingController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    $plans = \App\Models\SubscriptionPlan::where('is_active', true)->get();
    return view('landing', compact('plans'));
})->name('landing');

// Public Informational Pages
Route::get('/tentang-kami', [PageController::class, 'about'])->name('pages.about');
Route::get('/hubungi-kami', [PageController::class, 'contact'])->name('pages.contact');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Superadmin SaaS Management
    Route::middleware(['role:Superadmin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.plans.index');
        });
        Route::get('/plans', [SubscriptionPlanController::class, 'index'])->name('plans.index');
        Route::post('/plans', [SubscriptionPlanController::class, 'store'])->name('plans.store');
        Route::put('/plans/{plan}', [SubscriptionPlanController::class, 'update'])->name('plans.update');

        Route::get('/subscriptions', [TenantSubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::put('/subscriptions/{tenant}', [TenantSubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::post('/subscriptions/{tenant}/toggle', [TenantSubscriptionController::class, 'toggleStatus'])->name('subscriptions.toggle');

        Route::get('/pages', [PageManagementController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [PageManagementController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [PageManagementController::class, 'update'])->name('pages.update');

        Route::get('/waha-settings', [WahaSettingController::class, 'index'])->name('waha.index');
        Route::put('/waha-settings', [WahaSettingController::class, 'update'])->name('waha.update');
        Route::post('/waha-settings/test', [WahaSettingController::class, 'testConnection'])->name('waha.test');


        // Role & Permission Management (Role Has Permission RBAC)
        Route::get('/roles', [\App\Http\Controllers\Superadmin\RolePermissionController::class, 'index'])->name('roles.index');
        Route::put('/roles/{role}', [\App\Http\Controllers\Superadmin\RolePermissionController::class, 'updateRolePermissions'])->name('roles.update');
        Route::post('/roles', [\App\Http\Controllers\Superadmin\RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::post('/permissions', [\App\Http\Controllers\Superadmin\RolePermissionController::class, 'storePermission'])->name('permissions.store');
    });

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
    Route::get('/expenses/report', [ExpenseController::class, 'report'])->name('expenses.report');
    Route::get('/expenses/categories', [ExpenseCategoryController::class, 'index'])->name('expenses.categories.index');
    Route::post('/expenses/categories', [ExpenseCategoryController::class, 'store'])->name('expenses.categories.store');
    Route::put('/expenses/categories/{category}', [ExpenseCategoryController::class, 'update'])->name('expenses.categories.update');
    Route::delete('/expenses/categories/{category}', [ExpenseCategoryController::class, 'destroy'])->name('expenses.categories.destroy');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/export-pdf', [ExpenseController::class, 'exportPdf'])->name('expenses.export-pdf');
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::post('/expenses/{expense}/status', [ExpenseController::class, 'updateStatus'])->name('expenses.update-status');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // E-Rapor Engine & PDF Download
    Route::get('/erapor', [ERaporController::class, 'index'])->name('erapor.index');
    Route::post('/erapor/assessment', [ERaporController::class, 'storeAssessment'])->name('erapor.assessment.store');
    Route::get('/erapor/pdf/{siswa}', [ERaporController::class, 'exportPdf'])->name('erapor.pdf');
});
