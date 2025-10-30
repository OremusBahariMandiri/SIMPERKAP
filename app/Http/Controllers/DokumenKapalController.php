<?php

namespace App\Http\Controllers;

use App\Exports\DokumenKapalExport;
use App\Models\DokumenKapal;
use App\Models\Kapal;
use App\Models\KategoriDokumen;
use App\Models\NamaDokumen;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

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

        // Get all nama dokumen with their kategori relationships
        $namaDokumen = NamaDokumen::with('kategoriDokumen')->get();

        // Create a grouped array for JavaScript
        $namaDokumenByKategori = [];
        foreach ($namaDokumen as $dokumen) {
            $kategoriId = $dokumen->id_kode_a06;
            if (!isset($namaDokumenByKategori[$kategoriId])) {
                $namaDokumenByKategori[$kategoriId] = [];
            }
            $namaDokumenByKategori[$kategoriId][] = [
                'id_kode' => $dokumen->id_kode,
                'nama_dok' => $dokumen->nama_dok
            ];
        }

        // Generate ID automatically
        $newId = $this->generateId('B01', 'b01_dokumen_kpl');

        return view('dokumen-kapal.create', compact('kapal', 'kategoriDokumen', 'namaDokumen', 'namaDokumenByKategori', 'newId'));
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

            // Get file extension
            $extension = $file->getClientOriginalExtension();

            // Clean string from invalid characters for filename
            $sanitizeFileName = function ($string) {
                // Replace all invalid characters with dash
                $cleaned = preg_replace('/[\/\\\:*?"<>|]/', '-', $string);
                // Remove excessive dashes
                $cleaned = preg_replace('/-+/', '-', $cleaned);
                // Trim dashes at beginning and end
                return trim($cleaned, '-');
            };

            // Get kapal name
            $kapal = Kapal::where('id_kode', $request->nama_kpl)->first();
            $namaKapal = $kapal ? $sanitizeFileName($kapal->nama_kpl) : 'unknown';

            // Get nama dokumen
            $namaDokumen = NamaDokumen::where('id_kode', $request->nama_dok)->first();
            $jenisDok = $namaDokumen ? $sanitizeFileName($namaDokumen->nama_dok) : 'unknown';

            // Format tanggal berakhir
            $tglBerakhir = $request->jenis_masa_berlaku == 'Tetap' ? 'Permanent' : Carbon::parse($request->tgl_berakhir_dok)->format('Ymd');

            // Clean filename components
            $cleanNoReg = $sanitizeFileName($request->no_reg);
            $cleanJenisDok = $sanitizeFileName($jenisDok);

            // Build the filename
            $fileName = $cleanNoReg . '_' . $cleanJenisDok . '_' . $namaKapal . '_' . $tglBerakhir . '.' . $extension;

            // Store file with the new name
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

        // Get all nama dokumen with their kategori relationships
        $namaDokumen = NamaDokumen::with('kategoriDokumen')->get();

        // Create a grouped array for JavaScript
        $namaDokumenByKategori = [];
        foreach ($namaDokumen as $dokumen) {
            $kategoriId = $dokumen->id_kode_a06;
            if (!isset($namaDokumenByKategori[$kategoriId])) {
                $namaDokumenByKategori[$kategoriId] = [];
            }
            $namaDokumenByKategori[$kategoriId][] = [
                'id_kode' => $dokumen->id_kode,
                'nama_dok' => $dokumen->nama_dok
            ];
        }

        return view('dokumen-kapal.edit', compact('dokumenKapal', 'kapal', 'kategoriDokumen', 'namaDokumen', 'namaDokumenByKategori'));
    }

    public function update(Request $request, DokumenKapal $dokumenKapal)
    {
        $request->validate([
            'nama_kpl' => 'required|exists:a05_dm_kapal,id_kode',
            'kategori_dok' => 'required|exists:a06_dm_kategori_dok,id_kode',
            'nama_dok' => 'required|exists:a07_dm_nama_dok,id_kode',
            'file_dok' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
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
            'penerbit_dok' => $request->penerbit_dok,
            'validasi_dok' => $request->jenis_masa_berlaku,
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
            if ($dokumenKapal->file_dok && Storage::disk('public')->exists('dokumen_kapal/' . $dokumenKapal->file_dok)) {
                Storage::disk('public')->delete('dokumen_kapal/' . $dokumenKapal->file_dok);
            }

            $file = $request->file('file_dok');

            // Get file extension
            $extension = $file->getClientOriginalExtension();

            // Clean string from invalid characters for filename
            $sanitizeFileName = function ($string) {
                // Replace all invalid characters with dash
                $cleaned = preg_replace('/[\/\\\:*?"<>|]/', '-', $string);
                // Remove excessive dashes
                $cleaned = preg_replace('/-+/', '-', $cleaned);
                // Trim dashes at beginning and end
                return trim($cleaned, '-');
            };

            // Get kapal name
            $kapal = Kapal::where('id_kode', $request->nama_kpl)->first();
            $namaKapal = $kapal ? $sanitizeFileName($kapal->nama_kpl) : 'unknown';

            // Get nama dokumen
            $namaDokumen = NamaDokumen::where('id_kode', $request->nama_dok)->first();
            $jenisDok = $namaDokumen ? $sanitizeFileName($namaDokumen->nama_dok) : 'unknown';

            // Format tanggal berakhir
            $tglBerakhir = $request->jenis_masa_berlaku == 'Tetap' ? 'Permanent' : Carbon::parse($request->tgl_berakhir_dok)->format('Ymd');

            // Clean filename components
            $cleanNoReg = $sanitizeFileName($dokumenKapal->no_reg);
            $cleanJenisDok = $sanitizeFileName($jenisDok);

            // Build the filename
            $fileName = $cleanNoReg . '_' . $cleanJenisDok . '_' . $namaKapal . '_' . $tglBerakhir . '.' . $extension;

            // Store file with the new name
            $file->storeAs('dokumen_kapal', $fileName, 'public');
            $data['file_dok'] = $fileName;
        }

        $dokumenKapal->update($data);

        return redirect()->route('dokumen-kapal.index')
            ->with('success', 'Dokumen Kapal berhasil diperbarui.');
    }

    public function destroy(DokumenKapal $dokumenKapal)
    {
        // Delete file from storage if exists
        if ($dokumenKapal->file_dok && Storage::disk('public')->exists('dokumen_kapal/' . $dokumenKapal->file_dok)) {
            Storage::disk('public')->delete('dokumen_kapal/' . $dokumenKapal->file_dok);
        }

        $dokumenKapal->delete();

        return redirect()->route('dokumen-kapal.index')
            ->with('success', 'Dokumen Kapal berhasil dihapus.');
    }

    public function download(DokumenKapal $dokumenKapal)
    {
        if (!$dokumenKapal->file_dok) {
            return redirect()->back()->with('error', 'Tidak ada file yang tersedia untuk diunduh.');
        }

        $filePath = storage_path('app/public/dokumen_kapal/' . $dokumenKapal->file_dok);

        // Check if file exists
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, $dokumenKapal->file_dok);
    }

    public function monitoring()
    {
        $kategoriDokumen = KategoriDokumen::all();
        return view('dokumen-kapal.monitoring', compact('kategoriDokumen'));
    }

    public function detail(DokumenKapal $dokumenKapal)
    {
        $dokumenKapal->load(['kapal', 'kategoriDokumen', 'namaDokumen']);

        // Format dates for display
        if ($dokumenKapal->tgl_terbit_dok) {
            $dokumenKapal->tgl_terbit_dok_formatted = Carbon::parse($dokumenKapal->tgl_terbit_dok)->format('d-m-Y');
        }
        if ($dokumenKapal->tgl_berakhir_dok) {
            $dokumenKapal->tgl_berakhir_dok_formatted = Carbon::parse($dokumenKapal->tgl_berakhir_dok)->format('d-m-Y');
        }
        if ($dokumenKapal->tgl_peringatan) {
            $dokumenKapal->tgl_peringatan_formatted = Carbon::parse($dokumenKapal->tgl_peringatan)->format('d-m-Y');
        }

        return view('dokumen-kapal.detail', compact('dokumenKapal'));
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

    /**
     * Export dokumen kapal ke Excel berdasarkan filter
     */
    public function exportExcel(Request $request)
    {
        // Ambil parameter filter
        $filters = [
            'noreg' => $request->filter_noreg,
            'kapal' => $request->filter_kapal,
            'kategori' => $request->filter_kategori,
            'nama_dok' => $request->filter_nama_dok,
            'tgl_terbit_from' => $request->filter_tgl_terbit_from,
            'tgl_terbit_to' => $request->filter_tgl_terbit_to,
            'tgl_berakhir_from' => $request->filter_tgl_berakhir_from,
            'tgl_berakhir_to' => $request->filter_tgl_berakhir_to,
            'status' => $request->filter_status,
        ];

        // Query data berdasarkan filter
        $query = DokumenKapal::with(['kapal', 'kategoriDokumen', 'namaDokumen']);

        // Filter No Registrasi
        if ($request->filled('filter_noreg')) {
            $query->where('no_reg', 'like', '%' . $request->filter_noreg . '%');
        }

        // Filter Kapal
        if ($request->filled('filter_kapal')) {
            $query->whereHas('kapal', function ($q) use ($request) {
                $q->where('nama_kpl', $request->filter_kapal);
            });
        }

        // Filter Kategori
        if ($request->filled('filter_kategori')) {
            $query->whereHas('kategoriDokumen', function ($q) use ($request) {
                $q->where('kategori_dok', $request->filter_kategori);
            });
        }

        // Filter Nama Dokumen
        if ($request->filled('filter_nama_dok')) {
            $query->whereHas('namaDokumen', function ($q) use ($request) {
                $q->where('nama_dok', $request->filter_nama_dok);
            });
        }

        // Filter Tanggal Terbit
        if ($request->filled('filter_tgl_terbit_from')) {
            $query->where('tgl_terbit_dok', '>=', $request->filter_tgl_terbit_from);
        }
        if ($request->filled('filter_tgl_terbit_to')) {
            $query->where('tgl_terbit_dok', '<=', $request->filter_tgl_terbit_to);
        }

        // Filter Tanggal Berakhir
        if ($request->filled('filter_tgl_berakhir_from')) {
            $query->where('tgl_berakhir_dok', '>=', $request->filter_tgl_berakhir_from);
        }
        if ($request->filled('filter_tgl_berakhir_to')) {
            $query->where('tgl_berakhir_dok', '<=', $request->filter_tgl_berakhir_to);
        }

        // Filter Status Dokumen
        if ($request->filled('filter_status')) {
            if ($request->filter_status === 'Valid') {
                $query->where('status_dok', 'Berlaku')
                    ->where(function ($q) {
                        $q->whereNull('tgl_berakhir_dok')
                            ->orWhere('tgl_berakhir_dok', '>', now()->addDays(30));
                    });
            } else if ($request->filter_status === 'Warning') {
                $query->where('status_dok', 'Berlaku')
                    ->where('tgl_berakhir_dok', '>', now())
                    ->where('tgl_berakhir_dok', '<=', now()->addDays(30));
            } else if ($request->filter_status === 'Expired') {
                $query->where(function ($q) {
                    $q->where('status_dok', 'Tidak Berlaku')
                        ->orWhere(function ($subq) {
                            $subq->whereNotNull('tgl_berakhir_dok')
                                ->where('tgl_berakhir_dok', '<', now());
                        });
                });
            }
        }

        $dokumenKapal = $query->get();

        // Format tanggal untuk nama file
        $currentDate = now()->format('d-m-Y_H-i-s');
        $fileName = 'Dokumen_Kapal_' . $currentDate . '.xlsx';

        return Excel::download(new DokumenKapalExport($dokumenKapal, $filters), $fileName);
    }
}
