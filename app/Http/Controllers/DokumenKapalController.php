<?php

namespace App\Http\Controllers;

use App\Models\DokumenKapal;
use App\Models\Kapal;
use App\Models\KategoriDokumen;
use App\Models\NamaDokumen;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class DokumenKapalController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:dokumen_kapal')->only('index', 'show');
        $this->middleware('check.access:dokumen_kapal,tambah')->only('create', 'store');
        $this->middleware('check.access:dokumen_kapal,ubah')->only('edit', 'update');
        $this->middleware('check.access:dokumen_kapal,hapus')->only('destroy');
        $this->middleware('check.access:dokumen_kapal,download')->only('download');
        $this->middleware('check.access:dokumen_kapal,detail')->only('detail');
        $this->middleware('check.access:dokumen_kapal,monitoring')->only('monitoring');
    }

    public function index()
    {
        $dokumenKapal = DokumenKapal::with(['kapal', 'kategoriDokumen', 'namaDokumen'])->get();
        return view('dokumen-kapal.index', compact('dokumenKapal'));
    }

    public function create()
    {
        $kapal = Kapal::all();
        $kategoriDokumen = KategoriDokumen::all();
        $namaDokumen = NamaDokumen::all();

        // Generar ID automático
        $newId = $this->generateId('B01', 'b01_dokumen_kpl');

        return view('dokumen-kapal.create', compact('kapal', 'kategoriDokumen', 'namaDokumen', 'newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_reg' => 'required|unique:b01_dokumen_kpl',
            'nama_kpl' => 'required|exists:a05_dm_kapal,id_kode',
            'kategori_dok' => 'required|exists:a06_dm_kategori_dok,id_kode',
            'nama_dok' => 'required|exists:a07_dm_nama_dok,id_kode',
            'file_dok' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Generar ID si no está presente
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('B01', 'b01_dokumen_kpl');
        } else {
            $id_kode = $request->id_kode;
        }

        // Hitung masa berlaku secara otomatis
        $masa_berlaku = 'Tetap';
        if ($request->jenis_masa_berlaku == 'Perpanjangan' && $request->filled('tgl_terbit_dok') && $request->filled('tgl_berakhir_dok')) {
            $tglTerbit = Carbon::parse($request->tgl_terbit_dok);
            $tglBerakhir = Carbon::parse($request->tgl_berakhir_dok);

            // Hitung selisih tahun, bulan, dan hari
            $years = $tglBerakhir->diffInYears($tglTerbit);
            $months = $tglBerakhir->copy()->subYears($years)->diffInMonths($tglTerbit);
            $days = $tglBerakhir->copy()->subYears($years)->subMonths($months)->diffInDays($tglTerbit);

            $masa_berlaku = '';
            if ($years > 0) $masa_berlaku .= $years . ' thn ';
            if ($months > 0) $masa_berlaku .= $months . ' bln ';
            if ($days > 0) $masa_berlaku .= $days . ' hri';
            $masa_berlaku = trim($masa_berlaku) ?: '0 hri';
        }

        // Hitung masa peringatan secara otomatis
        $masa_peringatan = '-';
        if ($request->filled('tgl_peringatan') && $request->filled('tgl_berakhir_dok')) {
            $tglPengingat = Carbon::parse($request->tgl_peringatan);
            $tglBerakhir = Carbon::parse($request->tgl_berakhir_dok);

            // Validasi tanggal peringatan tidak boleh setelah tanggal berakhir
            if ($tglPengingat->lte($tglBerakhir)) {
                $days = $tglBerakhir->diffInDays($tglPengingat);
                $masa_peringatan = $days . ' hari';
            } else {
                return redirect()->back()->withInput()->with('error', 'Tanggal peringatan harus sebelum tanggal berakhir');
            }
        }

        $data = [
            'id_kode' => $id_kode,
            'no_reg' => $request->no_reg,
            'nama_kpl' => $request->nama_kpl,
            'kategori_dok' => $request->kategori_dok,
            'nama_dok' => $request->nama_dok,
            'penerbit_dok' => $request->penerbit_dok,
            'validasi_dok' => $request->jenis_masa_berlaku,
            'tgl_terbit_dok' => $request->tgl_terbit_dok,
            'tgl_berakhir_dok' => $request->jenis_masa_berlaku == 'Tetap' ? null : $request->tgl_berakhir_dok,
            'masa_berlaku' => $masa_berlaku,
            'tgl_peringatan' => $request->jenis_masa_berlaku == 'Tetap' ? null : $request->tgl_peringatan,
            'masa_peringatan' => $masa_peringatan,
            'catatan' => $request->catatan,
            'status_dok' => $request->status_dok,
            'created_by' => auth()->user()->id_kode ?? null,
        ];

        if ($request->hasFile('file_dok')) {
            $file = $request->file('file_dok');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('dokumen_kapal', $fileName, 'public');
            $data['file_dok'] = $fileName;
        }

        DokumenKapal::create($data);

        return redirect()->route('dokumen-kapal.index')
            ->with('success', 'Dokumen Kapal berhasil dibuat.');
    }

    public function show(DokumenKapal $dokumenKapal)
    {
        $dokumenKapal->load(['kapal', 'kategoriDokumen', 'namaDokumen']);
        return view('dokumen-kapal.show', compact('dokumenKapal'));
    }

    public function edit(DokumenKapal $dokumenKapal)
    {
        $kapal = Kapal::all();
        $kategoriDokumen = KategoriDokumen::all();
        $namaDokumen = NamaDokumen::all();
        return view('dokumen-kapal.edit', compact('dokumenKapal', 'kapal', 'kategoriDokumen', 'namaDokumen'));
    }

    public function update(Request $request, DokumenKapal $dokumenKapal)
    {
        $request->validate([
            'nama_kpl' => 'required|exists:a05_dm_kapal,id_kode',
            'kategori_dok' => 'required|exists:a06_dm_kategori_dok,id_kode',
            'nama_dok' => 'required|exists:a07_dm_nama_dok,id_kode',
            'file_dok' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Hitung masa berlaku secara otomatis
        $masa_berlaku = 'Tetap';
        if ($request->jenis_masa_berlaku == 'Perpanjangan' && $request->filled('tgl_terbit_dok') && $request->filled('tgl_berakhir_dok')) {
            $tglTerbit = Carbon::parse($request->tgl_terbit_dok);
            $tglBerakhir = Carbon::parse($request->tgl_berakhir_dok);

            // Hitung selisih tahun, bulan, dan hari
            $years = $tglBerakhir->diffInYears($tglTerbit);
            $months = $tglBerakhir->copy()->subYears($years)->diffInMonths($tglTerbit);
            $days = $tglBerakhir->copy()->subYears($years)->subMonths($months)->diffInDays($tglTerbit);

            $masa_berlaku = '';
            if ($years > 0) $masa_berlaku .= $years . ' thn ';
            if ($months > 0) $masa_berlaku .= $months . ' bln ';
            if ($days > 0) $masa_berlaku .= $days . ' hri';
            $masa_berlaku = trim($masa_berlaku) ?: '0 hri';
        }

        // Hitung masa peringatan secara otomatis
        $masa_peringatan = '-';
        if ($request->filled('tgl_peringatan') && $request->filled('tgl_berakhir_dok')) {
            $tglPengingat = Carbon::parse($request->tgl_peringatan);
            $tglBerakhir = Carbon::parse($request->tgl_berakhir_dok);

            // Validasi tanggal peringatan tidak boleh setelah tanggal berakhir
            if ($tglPengingat->lte($tglBerakhir)) {
                $days = $tglBerakhir->diffInDays($tglPengingat);
                $masa_peringatan = $days . ' hari';
            } else {
                return redirect()->back()->withInput()->with('error', 'Tanggal peringatan harus sebelum tanggal berakhir');
            }
        }

        $data = [
            'nama_kpl' => $request->nama_kpl,
            'kategori_dok' => $request->kategori_dok,
            'nama_dok' => $request->nama_dok,
            'validasi_dok' => $request->jenis_masa_berlaku,
            'penerbit_dok' => $request->penerbit_dok,
            'validasi_dok' => $request->validasi_dok,
            'tgl_terbit_dok' => $request->tgl_terbit_dok,
            'tgl_berakhir_dok' => $request->jenis_masa_berlaku == 'Tetap' ? null : $request->tgl_berakhir_dok,
            'masa_berlaku' => $masa_berlaku,
            'tgl_peringatan' => $request->jenis_masa_berlaku == 'Tetap' ? null : $request->tgl_peringatan,
            'masa_peringatan' => $masa_peringatan,
            'catatan' => $request->catatan,
            'status_dok' => $request->status_dok,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        if ($request->hasFile('file_dok')) {
            // Delete old file if exists
            if ($dokumenKapal->file_dok) {
                Storage::disk('public')->delete('dokumen_kapal/' . $dokumenKapal->file_dok);
            }

            $file = $request->file('file_dok');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('dokumen_kapal', $fileName, 'public');
            $data['file_dok'] = $fileName;
        }

        $dokumenKapal->update($data);

        return redirect()->route('dokumen-kapal.index')
            ->with('success', 'Dokumen Kapal berhasil diperbarui.');
    }

    public function destroy(DokumenKapal $dokumenKapal)
    {
        // Delete file if exists
        if ($dokumenKapal->file_dok) {
            Storage::disk('public')->delete('dokumen_kapal/' . $dokumenKapal->file_dok);
        }

        $dokumenKapal->delete();

        return redirect()->route('dokumen-kapal.index')
            ->with('success', 'Dokumen Kapal berhasil dihapus.');
    }

    public function download(DokumenKapal $dokumenKapal)
    {
        if (!$dokumenKapal->file_dok) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $path = storage_path('app/public/dokumen_kapal/' . $dokumenKapal->file_dok);

        if (!file_exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Mendapatkan ekstensi dari file asli
        $originalExtension = pathinfo($dokumenKapal->file_dok, PATHINFO_EXTENSION);

        // Format tanggal terbit untuk nama file
        $tanggalTerbit = $dokumenKapal->tgl_terbit_dok ? Carbon::parse($dokumenKapal->tgl_terbit_dok)->format('Ymd') : date('Ymd');

        // Membersihkan string dari karakter yang tidak valid untuk nama file
        $sanitizeFileName = function ($string) {
            // Pertama ganti semua karakter tidak valid dengan tanda strip
            $cleaned = preg_replace('/[\/\\\:*?"<>|]/', '-', $string);
            // Hilangkan kemungkinan tanda strip berlebih
            $cleaned = preg_replace('/-+/', '-', $cleaned);
            // Trim tanda strip di awal dan akhir
            return trim($cleaned, '-');
        };

        // Membersihkan komponen nama file
        $cleanNoReg = $sanitizeFileName($dokumenKapal->no_reg);
        $cleanNamaDok = $dokumenKapal->namaDokumen ? $sanitizeFileName($dokumenKapal->namaDokumen->nama_dok) : 'dokumen';
        $cleanNamaKapal = $dokumenKapal->kapal ? $sanitizeFileName($dokumenKapal->kapal->nama_kpl) : 'kapal';

        // Format nama file: NoReg_NamaDokumen_NamaKapal_TanggalTerbit
        $newFileName = $cleanNoReg . '_' . $cleanNamaDok . '_' . $cleanNamaKapal . '_' . $tanggalTerbit . '.' . $originalExtension;

        return response()->download($path, $newFileName);
    }

    public function detail(DokumenKapal $dokumenKapal)
    {
        $dokumenKapal->load(['kapal', 'kategoriDokumen', 'namaDokumen']);
        return view('dokumen-kapal.detail', compact('dokumenKapal'));
    }

    public function monitoring()
    {
        $dokumen = DokumenKapal::with(['kapal', 'kategoriDokumen', 'namaDokumen'])
            ->whereNotNull('tgl_berakhir_dok')
            ->get();

        // Menghitung dokumen yang akan expired dalam 30, 60, 90 hari
        $today = now();
        $expired30 = $dokumen->filter(function ($item) use ($today) {
            return $item->tgl_berakhir_dok && $today->diffInDays($item->tgl_berakhir_dok, false) <= 30 && $today->diffInDays($item->tgl_berakhir_dok, false) >= 0;
        });

        $expired60 = $dokumen->filter(function ($item) use ($today) {
            return $item->tgl_berakhir_dok && $today->diffInDays($item->tgl_berakhir_dok, false) <= 60 && $today->diffInDays($item->tgl_berakhir_dok, false) > 30;
        });

        $expired90 = $dokumen->filter(function ($item) use ($today) {
            return $item->tgl_berakhir_dok && $today->diffInDays($item->tgl_berakhir_dok, false) <= 90 && $today->diffInDays($item->tgl_berakhir_dok, false) > 60;
        });

        $expired = $dokumen->filter(function ($item) use ($today) {
            return $item->tgl_berakhir_dok && $today->diffInDays($item->tgl_berakhir_dok, false) < 0;
        });

        return view('dokumen-kapal.monitoring', compact('expired30', 'expired60', 'expired90', 'expired'));
    }

    /**
     * Get statistics for document status (expired, warning)
     */
    public function getDocumentStats()
    {
        // Hitung dokumen yang expired berdasarkan TglPengingat atau TglBerakhirDok
        $expiredCount = DokumenKapal::where(function ($query) {
            // Dokumen dengan Tanggal Pengingat yang sudah lewat atau hari ini
            $query->whereNotNull('tgl_peringatan')
                ->where('tgl_peringatan', '<=', now());
        })->orWhere(function ($query) {
            // Atau dokumen dengan Tanggal Berakhir yang sudah lewat
            $query->whereNotNull('tgl_berakhir_dok')
                ->where('tgl_berakhir_dok', '<', now())
                // Dan tidak memiliki TglPengingat yang sudah tercakup di kondisi sebelumnya
                ->where(function ($q) {
                    $q->whereNull('tgl_peringatan')
                        ->orWhere('tgl_peringatan', '>', now());
                });
        })->count();

        // Hitung dokumen yang akan expired (warning)
        $warningCount = DokumenKapal::where(function ($query) {
            // Dokumen dengan Tanggal Pengingat dalam 30 hari ke depan
            $query->whereNotNull('tgl_peringatan')
                ->where('tgl_peringatan', '>', now())
                ->where('tgl_peringatan', '<=', now()->addDays(30));
        })->orWhere(function ($query) {
            // Atau dokumen dengan Tanggal Berakhir dalam 30 hari ke depan
            $query->whereNotNull('tgl_berakhir_dok')
                ->where('tgl_berakhir_dok', '>=', now())
                ->where('tgl_berakhir_dok', '<=', now()->addDays(30))
                // Dan tidak memiliki TglPengingat yang sudah tercakup di kondisi sebelumnya
                ->where(function ($q) {
                    $q->whereNull('tgl_peringatan')
                        ->orWhere('tgl_peringatan', '>', now()->addDays(30));
                });
        })->count();

        return response()->json([
            'expired' => $expiredCount,
            'warning' => $warningCount
        ]);
    }

    /**
     * Get recent documents for dashboard
     */
    public function getRecentDocuments()
    {
        $recentDocuments = DokumenKapal::with(['kapal', 'kategoriDokumen', 'namaDokumen'])
            ->latest('tgl_terbit_dok')
            ->take(5)
            ->get(['id', 'id_kode', 'no_reg', 'nama_kpl', 'kategori_dok', 'nama_dok', 'tgl_terbit_dok', 'tgl_berakhir_dok']);

        return response()->json($recentDocuments);
    }

    /**
     * Get document count by status for dashboard
     */
    public function getDocumentsByStatus()
    {
        // Dokumen aktif (tanggal berakhir > hari ini + 30 hari)
        $aktif = DokumenKapal::whereNotNull('tgl_berakhir_dok')
            ->where('tgl_berakhir_dok', '>', now())
            ->where('tgl_berakhir_dok', '>', now()->addDays(30))
            ->count();

        // Dokumen hampir kedaluwarsa (tanggal berakhir dalam 30 hari)
        $hampirKedaluwarsa = DokumenKapal::whereNotNull('tgl_berakhir_dok')
            ->where('tgl_berakhir_dok', '>', now())
            ->where('tgl_berakhir_dok', '<=', now()->addDays(30))
            ->count();

        // Dokumen kedaluwarsa (tanggal berakhir <= hari ini)
        $kedaluwarsa = DokumenKapal::whereNotNull('tgl_berakhir_dok')
            ->where('tgl_berakhir_dok', '<=', now())
            ->count();

        // Dokumen tetap (tidak ada tanggal berakhir atau jenis masa berlaku tetap)
        $tetap = DokumenKapal::where(function ($query) {
            $query->whereNull('tgl_berakhir_dok')
                ->orWhere('masa_berlaku', 'Tetap');
        })->count();

        return response()->json([
            'aktif' => $aktif,
            'hampir_kedaluwarsa' => $hampirKedaluwarsa,
            'kedaluwarsa' => $kedaluwarsa,
            'tetap' => $tetap,
            'total' => DokumenKapal::count()
        ]);
    }


    public function viewDocument(DokumenKapal $dokumenKapal)
    {
        // Pastikan file ada
        if (!$dokumenKapal->file_dok) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Path file
        $filePath = storage_path('app/public/dokumen_kapal/' . $dokumenKapal->file_dok);


        // Cek apakah file ada
        if (!file_exists($filePath)) {
            // Try alternative path (in case path in database is different)
            $alternativePath = storage_path('app/public/dokumen-kapal/' . $dokumenKapal->file_dok);

            if (file_exists($alternativePath)) {
                $filePath = $alternativePath;
            } else {
                return back()->with('error', 'File tidak ditemukan di sistem penyimpanan.');
            }
        }

        // Dapatkan informasi file
        $fileInfo = pathinfo($filePath);
        $extension = strtolower($fileInfo['extension']);

        // Tentukan content type berdasarkan ekstensi file
        $contentTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        $contentType = $contentTypes[$extension] ?? 'application/octet-stream';

        // Untuk file yang dapat ditampilkan di browser (PDF, gambar)
        if (in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'])) {
            return response()->file($filePath, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'inline; filename="' . $dokumenKapal->file_dok . '"'
            ]);
        }

        // Jika file tidak dapat dipreview, redirect ke download
        return redirect()->route('dokumen-kapal.download', $dokumenKapal);
    }
}