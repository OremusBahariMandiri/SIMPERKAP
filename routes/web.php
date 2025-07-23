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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipParticularController;
use App\Http\Controllers\UserAccessController;
use App\Models\ShipParticular;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Route group dengan middleware auth
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Resource routes
    Route::resource('users', UserController::class);
    Route::resource('perusahaan', PerusahaanController::class);
    Route::resource('jenis-kapal', JenisKapalController::class);
    Route::resource('kapal', KapalController::class);
    Route::resource('kategori-dokumen', KategoriDokumenController::class);
    Route::resource('nama-dokumen', NamaDokumenController::class);
    Route::resource('dokumen-kapal', DokumenKapalController::class);
    Route::resource('ship-particular', ShipParticularController::class);
    
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    // Tambahan route untuk dokumen kapal
    Route::get('dokumen-kapal/{dokumenKapal}/download', [DokumenKapalController::class, 'download'])->name('dokumen-kapal.download');
    Route::get('ship-particular/{id}/download', [ShipParticularController::class, 'downloadFile'])
        ->name('ship-particular.download');
    // Route untuk view file (buka di tab baru)
    Route::get('ship-particular/{id}/view', [ShipParticularController::class, 'viewFile'])
        ->name('ship-particular.view');
    Route::get('dokumen-kapal/{dokumenKapal}/detail', [DokumenKapalController::class, 'detail'])->name('dokumen-kapal.detail');
    Route::get('dokumen-kapal-monitoring', [DokumenKapalController::class, 'monitoring'])->name('dokumen-kapal.monitoring');
    Route::get('dokumen-kapal/{dokumenKapal}/view-document', [DokumenKapalController::class, 'viewDocument'])->name('dokumen-kapal.viewDocument');
    Route::get('/nama-dokumen-by-kategori/{kategoriId}', [DokumenKapalController::class, 'getNamaDokumenByKategori'])->name('nama-dokumen-by-kategori');

    Route::get('user-access/{user}', [UserAccessController::class, 'show'])->name('user-access.show');
    Route::get('user-access/{user}/edit', [UserAccessController::class, 'edit'])->name('user-access.edit');
    Route::put('user-access/{user}', [UserAccessController::class, 'update'])->name('user-access.update');

    // Settings routes
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update-password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.update-password');
});
