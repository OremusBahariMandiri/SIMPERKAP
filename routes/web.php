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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecurityErrorController;
use App\Http\Controllers\ShipParticularController;
use App\Http\Controllers\ShipParticularsController;
use App\Http\Controllers\UserAccessController;
use App\Models\GolonganBarang;
use App\Models\NamaBarang;
use App\Models\ShipParticular;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes dengan Security dan Activity Hub Tracking
|--------------------------------------------------------------------------
*/

// Cek mode pengembangan - skip DDoS Protection di localhost
if (config('app.env') === 'local' || in_array(request()->ip(), ['127.0.0.1', '::1'])) {

    // Route untuk development tanpa middleware DDoS

    // Redirect root ke login
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
        Route::resource('golongan-barang', GolonganBarangController::class);
        Route::resource('kategori-barang', KategoriBarangController::class);
        Route::resource('jenis-barang', JenisBarangController::class);
        Route::resource('nama-barang', NamaBarangController::class);
        Route::resource('inventaris-kapal', InventarisKapalController::class);

        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/get-nama-barang-data/{id}', [App\Http\Controllers\InventarisKapalController::class, 'getNamaBarangData'])->name('get-nama-barang-data');

        // Tambahan route untuk dokumen kapal
        Route::get('dokumen-kapal/{dokumenKapal}/download', [DokumenKapalController::class, 'download'])->name('dokumen-kapal.download');
        Route::get('ship-particular/{id}/download', [ShipParticularController::class, 'downloadFile'])->name('ship-particular.download');
        Route::get('ship-particular/{id}/view', [ShipParticularController::class, 'viewFile'])->name('ship-particular.view');
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

        // Export route
        Route::post('/dokumen-kapal/export-excel', [DokumenKapalController::class, 'exportExcel'])->name('dokumen-kapal.export-excel');
    });

} else {
    // Produksi - gunakan full security dengan DDoS Protection dan Rate Limiting

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

        // Route group dengan middleware auth dan throttling
        Route::middleware(['auth', 'throttle.log:general'])->group(function () {
            // Dashboard - Rate limit lebih tinggi untuk halaman utama
            Route::middleware('throttle.log:dashboard')->group(function () {
                Route::get('/home', function () {
                    return view('home');
                })->name('home');
            });

            // Resource routes dengan rate limiting untuk create/update/delete

            // Users with rate limiting
            Route::prefix('users')->name('users.')->group(function () {
                // Create & Store
                Route::middleware(['throttle.log:100'])->group(function () {
                    Route::get('/create', [UserController::class, 'create'])->name('create');
                    Route::post('/', [UserController::class, 'store'])->name('store');
                });

                // Read operations
                Route::middleware(['throttle.log:read'])->group(function () {
                    Route::get('/', [UserController::class, 'index'])->name('index');
                    Route::get('/{user}', [UserController::class, 'show'])->name('show')->where('user', '[0-9]+');
                });

                // Update operations
                Route::middleware(['throttle.log:update'])->group(function () {
                    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                    Route::put('/{user}', [UserController::class, 'update'])->name('update');
                    Route::patch('/{user}', [UserController::class, 'update']);
                });

                // Delete operations
                Route::middleware(['throttle.log:delete'])->group(function () {
                    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
                });
            });

            Route::prefix('security')->name('security.')->group(function () {
                Route::get('/blocked', [SecurityErrorController::class, 'blocked'])->name('blocked');
                Route::get('/unauthorized', [SecurityErrorController::class, 'unauthorized'])->name('unauthorized');
                Route::get('/ip-not-whitelisted', [SecurityErrorController::class, 'ipNotWhitelisted'])->name('ip-not-whitelisted');
                Route::get('/error', [SecurityErrorController::class, 'securityError'])->name('error');
            });

            // User access
            Route::middleware(['throttle.log:admin'])->group(function () {
                Route::get('user-access/{user}', [UserAccessController::class, 'show'])->name('user-access.show');
                Route::get('user-access/{user}/edit', [UserAccessController::class, 'edit'])->name('user-access.edit');
                Route::put('user-access/{user}', [UserAccessController::class, 'update'])->name('user-access.update');
            });

            // Rest of resources with basic rate limiting
            Route::middleware(['throttle.log:read'])->group(function () {
                // Resource routes - Read operations
                Route::get('perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
                Route::get('perusahaan/{perusahaan}', [PerusahaanController::class, 'show'])->name('perusahaan.show');

                Route::get('jenis-kapal', [JenisKapalController::class, 'index'])->name('jenis-kapal.index');
                Route::get('jenis-kapal/{jenisKapal}', [JenisKapalController::class, 'show'])->name('jenis-kapal.show');

                Route::get('kapal', [KapalController::class, 'index'])->name('kapal.index');
                Route::get('kapal/{kapal}', [KapalController::class, 'show'])->name('kapal.show');

                Route::get('kategori-dokumen', [KategoriDokumenController::class, 'index'])->name('kategori-dokumen.index');
                Route::get('kategori-dokumen/{kategoriDokumen}', [KategoriDokumenController::class, 'show'])->name('kategori-dokumen.show');

                Route::get('nama-dokumen', [NamaDokumenController::class, 'index'])->name('nama-dokumen.index');
                Route::get('nama-dokumen/{namaDokumen}', [NamaDokumenController::class, 'show'])->name('nama-dokumen.show');

                Route::get('dokumen-kapal', [DokumenKapalController::class, 'index'])->name('dokumen-kapal.index');
                Route::get('dokumen-kapal/{dokumenKapal}', [DokumenKapalController::class, 'show'])->name('dokumen-kapal.show');
                Route::get('dokumen-kapal/{dokumenKapal}/detail', [DokumenKapalController::class, 'detail'])->name('dokumen-kapal.detail');
                Route::get('dokumen-kapal-monitoring', [DokumenKapalController::class, 'monitoring'])->name('dokumen-kapal.monitoring');
                Route::get('dokumen-kapal/{dokumenKapal}/view-document', [DokumenKapalController::class, 'viewDocument'])->name('dokumen-kapal.viewDocument');

                Route::get('ship-particular', [ShipParticularController::class, 'index'])->name('ship-particular.index');
                Route::get('ship-particular/{shipParticular}', [ShipParticularController::class, 'show'])->name('ship-particular.show');
                Route::get('ship-particular/{id}/view', [ShipParticularController::class, 'viewFile'])->name('ship-particular.view');

                // Inventaris routes
                Route::get('golongan-barang', [GolonganBarangController::class, 'index'])->name('golongan-barang.index');
                Route::get('golongan-barang/{golonganBarang}', [GolonganBarangController::class, 'show'])->name('golongan-barang.show');

                Route::get('kategori-barang', [KategoriBarangController::class, 'index'])->name('kategori-barang.index');
                Route::get('kategori-barang/{kategoriBarang}', [KategoriBarangController::class, 'show'])->name('kategori-barang.show');

                Route::get('jenis-barang', [JenisBarangController::class, 'index'])->name('jenis-barang.index');
                Route::get('jenis-barang/{jenisBarang}', [JenisBarangController::class, 'show'])->name('jenis-barang.show');

                Route::get('nama-barang', [NamaBarangController::class, 'index'])->name('nama-barang.index');
                Route::get('nama-barang/{namaBarang}', [NamaBarangController::class, 'show'])->name('nama-barang.show');

                Route::get('inventaris-kapal', [InventarisKapalController::class, 'index'])->name('inventaris-kapal.index');
                Route::get('inventaris-kapal/{inventarisKapal}', [InventarisKapalController::class, 'show'])->name('inventaris-kapal.show');
            });

            // Create operations
            Route::middleware(['throttle.log:100'])->group(function () {
                // Resource routes - Create operations
                Route::get('perusahaan/create', [PerusahaanController::class, 'create'])->name('perusahaan.create');
                Route::post('perusahaan', [PerusahaanController::class, 'store'])->name('perusahaan.store');

                Route::get('jenis-kapal/create', [JenisKapalController::class, 'create'])->name('jenis-kapal.create');
                Route::post('jenis-kapal', [JenisKapalController::class, 'store'])->name('jenis-kapal.store');

                Route::get('kapal/create', [KapalController::class, 'create'])->name('kapal.create');
                Route::post('kapal', [KapalController::class, 'store'])->name('kapal.store');

                Route::get('kategori-dokumen/create', [KategoriDokumenController::class, 'create'])->name('kategori-dokumen.create');
                Route::post('kategori-dokumen', [KategoriDokumenController::class, 'store'])->name('kategori-dokumen.store');

                Route::get('nama-dokumen/create', [NamaDokumenController::class, 'create'])->name('nama-dokumen.create');
                Route::post('nama-dokumen', [NamaDokumenController::class, 'store'])->name('nama-dokumen.store');

                Route::get('dokumen-kapal/create', [DokumenKapalController::class, 'create'])->name('dokumen-kapal.create');
                Route::post('dokumen-kapal', [DokumenKapalController::class, 'store'])->name('dokumen-kapal.store');

                Route::get('ship-particular/create', [ShipParticularController::class, 'create'])->name('ship-particular.create');
                Route::post('ship-particular', [ShipParticularController::class, 'store'])->name('ship-particular.store');

                // Inventaris routes
                Route::get('golongan-barang/create', [GolonganBarangController::class, 'create'])->name('golongan-barang.create');
                Route::post('golongan-barang', [GolonganBarangController::class, 'store'])->name('golongan-barang.store');

                Route::get('kategori-barang/create', [KategoriBarangController::class, 'create'])->name('kategori-barang.create');
                Route::post('kategori-barang', [KategoriBarangController::class, 'store'])->name('kategori-barang.store');

                Route::get('jenis-barang/create', [JenisBarangController::class, 'create'])->name('jenis-barang.create');
                Route::post('jenis-barang', [JenisBarangController::class, 'store'])->name('jenis-barang.store');

                Route::get('nama-barang/create', [NamaBarangController::class, 'create'])->name('nama-barang.create');
                Route::post('nama-barang', [NamaBarangController::class, 'store'])->name('nama-barang.store');

                Route::get('inventaris-kapal/create', [InventarisKapalController::class, 'create'])->name('inventaris-kapal.create');
                Route::post('inventaris-kapal', [InventarisKapalController::class, 'store'])->name('inventaris-kapal.store');
            });

            // Update operations
            Route::middleware(['throttle.log:update'])->group(function () {
                // Resource routes - Update operations
                Route::get('perusahaan/{perusahaan}/edit', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
                Route::put('perusahaan/{perusahaan}', [PerusahaanController::class, 'update'])->name('perusahaan.update');
                Route::patch('perusahaan/{perusahaan}', [PerusahaanController::class, 'update']);

                Route::get('jenis-kapal/{jenisKapal}/edit', [JenisKapalController::class, 'edit'])->name('jenis-kapal.edit');
                Route::put('jenis-kapal/{jenisKapal}', [JenisKapalController::class, 'update'])->name('jenis-kapal.update');
                Route::patch('jenis-kapal/{jenisKapal}', [JenisKapalController::class, 'update']);

                Route::get('kapal/{kapal}/edit', [KapalController::class, 'edit'])->name('kapal.edit');
                Route::put('kapal/{kapal}', [KapalController::class, 'update'])->name('kapal.update');
                Route::patch('kapal/{kapal}', [KapalController::class, 'update']);

                Route::get('kategori-dokumen/{kategoriDokumen}/edit', [KategoriDokumenController::class, 'edit'])->name('kategori-dokumen.edit');
                Route::put('kategori-dokumen/{kategoriDokumen}', [KategoriDokumenController::class, 'update'])->name('kategori-dokumen.update');
                Route::patch('kategori-dokumen/{kategoriDokumen}', [KategoriDokumenController::class, 'update']);

                Route::get('nama-dokumen/{namaDokumen}/edit', [NamaDokumenController::class, 'edit'])->name('nama-dokumen.edit');
                Route::put('nama-dokumen/{namaDokumen}', [NamaDokumenController::class, 'update'])->name('nama-dokumen.update');
                Route::patch('nama-dokumen/{namaDokumen}', [NamaDokumenController::class, 'update']);

                Route::get('dokumen-kapal/{dokumenKapal}/edit', [DokumenKapalController::class, 'edit'])->name('dokumen-kapal.edit');
                Route::put('dokumen-kapal/{dokumenKapal}', [DokumenKapalController::class, 'update'])->name('dokumen-kapal.update');
                Route::patch('dokumen-kapal/{dokumenKapal}', [DokumenKapalController::class, 'update']);

                Route::get('ship-particular/{shipParticular}/edit', [ShipParticularController::class, 'edit'])->name('ship-particular.edit');
                Route::put('ship-particular/{shipParticular}', [ShipParticularController::class, 'update'])->name('ship-particular.update');
                Route::patch('ship-particular/{shipParticular}', [ShipParticularController::class, 'update']);

                // Inventaris routes
                Route::get('golongan-barang/{golonganBarang}/edit', [GolonganBarangController::class, 'edit'])->name('golongan-barang.edit');
                Route::put('golongan-barang/{golonganBarang}', [GolonganBarangController::class, 'update'])->name('golongan-barang.update');
                Route::patch('golongan-barang/{golonganBarang}', [GolonganBarangController::class, 'update']);

                Route::get('kategori-barang/{kategoriBarang}/edit', [KategoriBarangController::class, 'edit'])->name('kategori-barang.edit');
                Route::put('kategori-barang/{kategoriBarang}', [KategoriBarangController::class, 'update'])->name('kategori-barang.update');
                Route::patch('kategori-barang/{kategoriBarang}', [KategoriBarangController::class, 'update']);

                Route::get('jenis-barang/{jenisBarang}/edit', [JenisBarangController::class, 'edit'])->name('jenis-barang.edit');
                Route::put('jenis-barang/{jenisBarang}', [JenisBarangController::class, 'update'])->name('jenis-barang.update');
                Route::patch('jenis-barang/{jenisBarang}', [JenisBarangController::class, 'update']);

                Route::get('nama-barang/{namaBarang}/edit', [NamaBarangController::class, 'edit'])->name('nama-barang.edit');
                Route::put('nama-barang/{namaBarang}', [NamaBarangController::class, 'update'])->name('nama-barang.update');
                Route::patch('nama-barang/{namaBarang}', [NamaBarangController::class, 'update']);

                Route::get('inventaris-kapal/{inventarisKapal}/edit', [InventarisKapalController::class, 'edit'])->name('inventaris-kapal.edit');
                Route::put('inventaris-kapal/{inventarisKapal}', [InventarisKapalController::class, 'update'])->name('inventaris-kapal.update');
                Route::patch('inventaris-kapal/{inventarisKapal}', [InventarisKapalController::class, 'update']);
            });

            // Delete operations
            Route::middleware(['throttle.log:delete'])->group(function () {
                // Resource routes - Delete operations
                Route::delete('perusahaan/{perusahaan}', [PerusahaanController::class, 'destroy'])->name('perusahaan.destroy');
                Route::delete('jenis-kapal/{jenisKapal}', [JenisKapalController::class, 'destroy'])->name('jenis-kapal.destroy');
                Route::delete('kapal/{kapal}', [KapalController::class, 'destroy'])->name('kapal.destroy');
                Route::delete('kategori-dokumen/{kategoriDokumen}', [KategoriDokumenController::class, 'destroy'])->name('kategori-dokumen.destroy');
                Route::delete('nama-dokumen/{namaDokumen}', [NamaDokumenController::class, 'destroy'])->name('nama-dokumen.destroy');
                Route::delete('dokumen-kapal/{dokumenKapal}', [DokumenKapalController::class, 'destroy'])->name('dokumen-kapal.destroy');
                Route::delete('ship-particular/{shipParticular}', [ShipParticularController::class, 'destroy'])->name('ship-particular.destroy');
                Route::delete('golongan-barang/{golonganBarang}', [GolonganBarangController::class, 'destroy'])->name('golongan-barang.destroy');
                Route::delete('kategori-barang/{kategoriBarang}', [KategoriBarangController::class, 'destroy'])->name('kategori-barang.destroy');
                Route::delete('jenis-barang/{jenisBarang}', [JenisBarangController::class, 'destroy'])->name('jenis-barang.destroy');
                Route::delete('nama-barang/{namaBarang}', [NamaBarangController::class, 'destroy'])->name('nama-barang.destroy');
                Route::delete('inventaris-kapal/{inventarisKapal}', [InventarisKapalController::class, 'destroy'])->name('inventaris-kapal.destroy');
            });

            // Download operations dengan throttling ketat
            Route::middleware(['throttle.log:download'])->group(function () {
                Route::get('dokumen-kapal/{dokumenKapal}/download', [DokumenKapalController::class, 'download'])->name('dokumen-kapal.download');
                Route::get('ship-particular/{id}/download', [ShipParticularController::class, 'downloadFile'])->name('ship-particular.download');
            });

            // Export dengan throttling
            Route::middleware(['throttle.log:export'])->group(function () {
                Route::post('/dokumen-kapal/export-excel', [DokumenKapalController::class, 'exportExcel'])->name('dokumen-kapal.export-excel');
            });

            // API dengan throttling
            Route::middleware(['throttle.log:api'])->group(function () {
                Route::get('/get-nama-barang-data/{id}', [App\Http\Controllers\InventarisKapalController::class, 'getNamaBarangData'])->name('get-nama-barang-data');
                Route::get('/nama-dokumen-by-kategori/{kategoriId}', [DokumenKapalController::class, 'getNamaDokumenByKategori'])->name('nama-dokumen-by-kategori');
            });

            // Settings dan Profile dengan throttling
            Route::middleware(['throttle.log:profile'])->group(function () {
                Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
                Route::post('/settings/update-password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.update-password');
            });
        });
    });
}