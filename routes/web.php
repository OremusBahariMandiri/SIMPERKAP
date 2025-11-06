<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\JenisKapalController;
use App\Http\Controllers\KapalController;
use App\Http\Controllers\KategoriDokumenController;
use App\Http\Controllers\NamaDokumenController;
use App\Http\Controllers\DokumenKapalController;
use App\Http\Controllers\GolonganBarangController;
use App\Http\Controllers\InventarisKapalController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\NamaBarangController;
use App\Http\Controllers\SecurityErrorController;
use App\Http\Controllers\ShipParticularController;
use App\Http\Controllers\UserAccessController;

/*
|--------------------------------------------------------------------------
| Web Routes dengan Security dan Activity Hub Tracking
|--------------------------------------------------------------------------
*/

// DDoS Protection untuk semua route publik
Route::middleware(['ddos', 'security.headers'])->group(function () {
    // Redirect root ke login
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Authentication Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Security error pages
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/blocked', [SecurityErrorController::class, 'blocked'])->name('blocked');
        Route::get('/unauthorized', [SecurityErrorController::class, 'unauthorized'])->name('unauthorized');
        Route::get('/ip-not-whitelisted', [SecurityErrorController::class, 'ipNotWhitelisted'])->name('ip-not-whitelisted');
        Route::get('/error', [SecurityErrorController::class, 'securityError'])->name('error');
    });

    // Route group dengan middleware auth dan throttling yang longgar
    Route::middleware(['auth', 'throttle.log:300'])->group(function () {
        // Dashboard
        Route::get('/home', function () {
            return view('home');
        })->name('home');

        // Resource routes - tanpa throttling per operasi
        Route::resource('users', UserController::class);
        Route::resource('perusahaan', PerusahaanController::class);
        Route::resource('jenis-kapal', JenisKapalController::class);
        Route::resource('kapal', KapalController::class);
        Route::resource('kategori-dokumen', KategoriDokumenController::class);
        Route::resource('nama-dokumen', NamaDokumenController::class);
        Route::resource('dokumen-kapal', DokumenKapalController::class);
        Route::resource('ship-particular', ShipParticularController::class);
        Route::resource('golongan-barang', GolonganBarangController::class);
        Route::resource('kategori-barang', KategoriBarangController::class);
        Route::resource('jenis-barang', JenisBarangController::class);
        Route::resource('nama-barang', NamaBarangController::class);
        Route::resource('inventaris-kapal', InventarisKapalController::class);

        // User access routes
        Route::get('user-access/{user}', [UserAccessController::class, 'show'])->name('user-access.show');
        Route::get('user-access/{user}/edit', [UserAccessController::class, 'edit'])->name('user-access.edit');
        Route::put('user-access/{user}', [UserAccessController::class, 'update'])->name('user-access.update');

        // Tambahan route untuk dokumen kapal
        Route::get('dokumen-kapal/{dokumenKapal}/download', [DokumenKapalController::class, 'download'])->name('dokumen-kapal.download');
        Route::get('dokumen-kapal/{dokumenKapal}/detail', [DokumenKapalController::class, 'detail'])->name('dokumen-kapal.detail');
        Route::get('dokumen-kapal-monitoring', [DokumenKapalController::class, 'monitoring'])->name('dokumen-kapal.monitoring');
        Route::get('dokumen-kapal/{dokumenKapal}/view-document', [DokumenKapalController::class, 'viewDocument'])->name('dokumen-kapal.viewDocument');

        // Ship particular routes
        Route::get('ship-particular/{id}/download', [ShipParticularController::class, 'downloadFile'])->name('ship-particular.download');
        Route::get('ship-particular/{id}/view', [ShipParticularController::class, 'viewFile'])->name('ship-particular.view');

        // API routes
        Route::get('/get-nama-barang-data/{id}', [InventarisKapalController::class, 'getNamaBarangData'])->name('get-nama-barang-data');
        Route::get('/nama-dokumen-by-kategori/{kategoriId}', [DokumenKapalController::class, 'getNamaDokumenByKategori'])->name('nama-dokumen-by-kategori');

        // Settings routes
        Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/update-password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.update-password');

        // Export route
        Route::post('/dokumen-kapal/export-excel', [DokumenKapalController::class, 'exportExcel'])->name('dokumen-kapal.export-excel');
    });
});