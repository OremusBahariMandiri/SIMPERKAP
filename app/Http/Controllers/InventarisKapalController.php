<?php

namespace App\Http\Controllers;

use App\Models\InventarisKapal;
use App\Models\Kapal;
use App\Models\NamaBarang;
use App\Models\KategoriBarang;
use App\Models\JenisBarang;
use App\Models\GolonganBarang;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InventarisKapalController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:inventaris_kapal')->only('index', 'show');
        $this->middleware('check.access:inventaris_kapal,tambah')->only('create', 'store');
        $this->middleware('check.access:inventaris_kapal,ubah')->only('edit', 'update');
        $this->middleware('check.access:inventaris_kapal,hapus')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventarisKapal = InventarisKapal::with(['kapal', 'namaBarang', 'kategoriBarang', 'jenisBarang', 'golonganBarang', 'creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inventaris-kapal.index', compact('inventarisKapal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kapal = Kapal::orderBy('nama_kpl', 'asc')->get();
        $namaBarang = NamaBarang::orderBy('nama_brg', 'asc')->get();
        $kategoriBarang = KategoriBarang::orderBy('kategori_brg', 'asc')->get();
        $jenisBarang = JenisBarang::orderBy('jenis_brg', 'asc')->get();
        $golonganBarang = GolonganBarang::orderBy('golongan_brg', 'asc')->get();

        // Generate ID otomatis
        $newId = $this->generateId('B03', 'b03_Inventaris_Kpl');

        return view('inventaris-kapal.create', compact('kapal', 'namaBarang', 'kategoriBarang', 'jenisBarang', 'golonganBarang', 'newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_kode_05' => 'required|exists:a05_dm_kapal,id_kode',
            'id_kode_11' => 'required|exists:a11_dm_nama_brg,id_kode',
            'id_kode_08' => 'required|exists:a08_dm_kategori_brg,id_kode',
            'id_kode_09' => 'required|exists:a09_dm_jenis_brg,id_kode',
            'id_kode_10' => 'required|exists:a10_dm_golongan_brg,id_kode',
            'no_kode_brg' => 'nullable|string|max:50',
            'no_kode_brg_subtitusi' => 'nullable|string|max:50',
            'tipe_brg' => 'nullable|string|max:255',
            'spesifikasi_brg' => 'nullable|string|max:65535',
            'satuan_brg' => 'nullable|string|max:50',
            'merek_brg' => 'nullable|string|max:255',
            'supplier_brg' => 'nullable|string|max:255',
            'lokasi_brg' => 'nullable|string|max:255',
            'keterangan_brg' => 'nullable|string|max:65535',
            'tgl_pengadaan_brg' => 'nullable|date',
            'no_pengadaan_brg' => 'nullable|string|max:50',
            'stock_awal' => 'nullable|numeric',
            'stock_masuk' => 'nullable|numeric',
            'stock_keluar' => 'nullable|numeric',
            'stock_akhir' => 'nullable|numeric',
            'stock_limit' => 'nullable|numeric',
            'file_dok' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
        ], [
            'id_kode_05.required' => 'Kapal harus dipilih',
            'id_kode_05.exists' => 'Kapal yang dipilih tidak valid',
            'id_kode_11.required' => 'Nama barang harus dipilih',
            'id_kode_11.exists' => 'Nama barang yang dipilih tidak valid',
            'id_kode_08.required' => 'Kategori barang harus dipilih',
            'id_kode_08.exists' => 'Kategori barang yang dipilih tidak valid',
            'id_kode_09.required' => 'Jenis barang harus dipilih',
            'id_kode_09.exists' => 'Jenis barang yang dipilih tidak valid',
            'id_kode_10.required' => 'Golongan barang harus dipilih',
            'id_kode_10.exists' => 'Golongan barang yang dipilih tidak valid',
            'file_dok.mimes' => 'File harus berupa jpeg, png, jpg, pdf, doc, atau docx',
            'file_dok.max' => 'Ukuran file maksimal 2MB',
        ]);

        // Generate ID jika tidak ada
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('B03', 'b03_Inventaris_Kpl');
        } else {
            $id_kode = $request->id_kode;
        }

        $data = [
            'id_kode' => $id_kode,
            'id_kode_05' => $request->id_kode_05,
            'id_kode_11' => $request->id_kode_11,
            'id_kode_08' => $request->id_kode_08,
            'id_kode_09' => $request->id_kode_09,
            'id_kode_10' => $request->id_kode_10,
            'no_kode_brg' => $request->no_kode_brg,
            'no_kode_brg_subtitusi' => $request->no_kode_brg_subtitusi,
            'tipe_brg' => $request->tipe_brg,
            'spesifikasi_brg' => $request->spesifikasi_brg,
            'satuan_brg' => $request->satuan_brg,
            'merek_brg' => $request->merek_brg,
            'supplier_brg' => $request->supplier_brg,
            'lokasi_brg' => $request->lokasi_brg,
            'keterangan_brg' => $request->keterangan_brg,
            'tgl_pengadaan_brg' => $request->tgl_pengadaan_brg,
            'no_pengadaan_brg' => $request->no_pengadaan_brg,
            'stock_awal' => $request->stock_awal,
            'stock_masuk' => $request->stock_masuk,
            'stock_keluar' => $request->stock_keluar,
            'stock_akhir' => $request->stock_akhir,
            'stock_limit' => $request->stock_limit,
            'created_by' => auth()->user()->id_kode ?? null,
        ];

        // Handle file upload
        if ($request->hasFile('file_dok')) {
            $file = $request->file('file_dok');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('inventaris_kapal', $filename, 'public');
            $data['file_dok'] = $path;
        }

        try {
            InventarisKapal::create($data);

            return redirect()->route('inventaris-kapal.index')
                ->with('success', 'Data Inventaris Kapal berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $inventarisKapal = InventarisKapal::with(['kapal', 'namaBarang', 'kategoriBarang', 'jenisBarang', 'golonganBarang', 'creator', 'updater'])->findOrFail($id);

        return view('inventaris-kapal.show', compact('inventarisKapal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $inventarisKapal = InventarisKapal::findOrFail($id);
        $kapal = Kapal::orderBy('nama_kpl', 'asc')->get();
        $namaBarang = NamaBarang::orderBy('nama_brg', 'asc')->get();
        $kategoriBarang = KategoriBarang::orderBy('kategori_brg', 'asc')->get();
        $jenisBarang = JenisBarang::orderBy('jenis_brg', 'asc')->get();
        $golonganBarang = GolonganBarang::orderBy('golongan_brg', 'asc')->get();

        return view('inventaris-kapal.edit', compact('inventarisKapal', 'kapal', 'namaBarang', 'kategoriBarang', 'jenisBarang', 'golonganBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $inventarisKapal = InventarisKapal::findOrFail($id);

        $request->validate([
            'id_kode_05' => 'required|exists:a05_dm_kapal,id_kode',
            'id_kode_11' => 'required|exists:a11_dm_nama_brg,id_kode',
            'id_kode_08' => 'required|exists:a08_dm_kategori_brg,id_kode',
            'id_kode_09' => 'required|exists:a09_dm_jenis_brg,id_kode',
            'id_kode_10' => 'required|exists:a10_dm_golongan_brg,id_kode',
            'no_kode_brg' => 'nullable|string|max:50',
            'no_kode_brg_subtitusi' => 'nullable|string|max:50',
            'tipe_brg' => 'nullable|string|max:255',
            'spesifikasi_brg' => 'nullable|string|max:65535',
            'satuan_brg' => 'nullable|string|max:50',
            'merek_brg' => 'nullable|string|max:255',
            'supplier_brg' => 'nullable|string|max:255',
            'lokasi_brg' => 'nullable|string|max:255',
            'keterangan_brg' => 'nullable|string|max:65535',
            'tgl_pengadaan_brg' => 'nullable|date',
            'no_pengadaan_brg' => 'nullable|string|max:50',
            'stock_awal' => 'nullable|numeric',
            'stock_masuk' => 'nullable|numeric',
            'stock_keluar' => 'nullable|numeric',
            'stock_akhir' => 'nullable|numeric',
            'stock_limit' => 'nullable|numeric',
            'file_dok' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
        ], [
            'id_kode_05.required' => 'Kapal harus dipilih',
            'id_kode_05.exists' => 'Kapal yang dipilih tidak valid',
            'id_kode_11.required' => 'Nama barang harus dipilih',
            'id_kode_11.exists' => 'Nama barang yang dipilih tidak valid',
            'id_kode_08.required' => 'Kategori barang harus dipilih',
            'id_kode_08.exists' => 'Kategori barang yang dipilih tidak valid',
            'id_kode_09.required' => 'Jenis barang harus dipilih',
            'id_kode_09.exists' => 'Jenis barang yang dipilih tidak valid',
            'id_kode_10.required' => 'Golongan barang harus dipilih',
            'id_kode_10.exists' => 'Golongan barang yang dipilih tidak valid',
            'file_dok.mimes' => 'File harus berupa jpeg, png, jpg, pdf, doc, atau docx',
            'file_dok.max' => 'Ukuran file maksimal 2MB',
        ]);

        $data = [
            'id_kode_05' => $request->id_kode_05,
            'id_kode_11' => $request->id_kode_11,
            'id_kode_08' => $request->id_kode_08,
            'id_kode_09' => $request->id_kode_09,
            'id_kode_10' => $request->id_kode_10,
            'no_kode_brg' => $request->no_kode_brg,
            'no_kode_brg_subtitusi' => $request->no_kode_brg_subtitusi,
            'tipe_brg' => $request->tipe_brg,
            'spesifikasi_brg' => $request->spesifikasi_brg,
            'satuan_brg' => $request->satuan_brg,
            'merek_brg' => $request->merek_brg,
            'supplier_brg' => $request->supplier_brg,
            'lokasi_brg' => $request->lokasi_brg,
            'keterangan_brg' => $request->keterangan_brg,
            'tgl_pengadaan_brg' => $request->tgl_pengadaan_brg,
            'no_pengadaan_brg' => $request->no_pengadaan_brg,
            'stock_awal' => $request->stock_awal,
            'stock_masuk' => $request->stock_masuk,
            'stock_keluar' => $request->stock_keluar,
            'stock_akhir' => $request->stock_akhir,
            'stock_limit' => $request->stock_limit,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        // Handle file upload
        if ($request->hasFile('file_dok')) {
            // Delete old file if exists
            if ($inventarisKapal->file_dok && Storage::disk('public')->exists($inventarisKapal->file_dok)) {
                Storage::disk('public')->delete($inventarisKapal->file_dok);
            }

            $file = $request->file('file_dok');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('inventaris_kapal', $filename, 'public');
            $data['file_dok'] = $path;
        }

        try {
            $inventarisKapal->update($data);

            return redirect()->route('inventaris-kapal.index')
                ->with('success', 'Data Inventaris Kapal berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $inventarisKapal = InventarisKapal::findOrFail($id);

        try {
            // Delete file if exists
            if ($inventarisKapal->file_dok && Storage::disk('public')->exists($inventarisKapal->file_dok)) {
                Storage::disk('public')->delete($inventarisKapal->file_dok);
            }

            $inventarisKapal->delete();

            return redirect()->route('inventaris-kapal.index')
                ->with('success', 'Data Inventaris Kapal berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}