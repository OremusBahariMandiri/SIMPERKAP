<?php

namespace App\Http\Controllers;

use App\Models\NamaBarang;
use App\Models\KategoriBarang;
use App\Models\JenisBarang;
use App\Models\GolonganBarang;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NamaBarangController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:nama_barang')->only('index', 'show');
        $this->middleware('check.access:nama_barang,tambah')->only('create', 'store');
        $this->middleware('check.access:nama_barang,ubah')->only('edit', 'update');
        $this->middleware('check.access:nama_barang,hapus')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $namaBarangs = NamaBarang::with(['kategoriBarang', 'jenisBarang', 'golonganbarang', 'creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('nama-barang.index', compact('namaBarangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriBarangs = KategoriBarang::orderBy('kategori_brg', 'asc')->get();
        $jenisBarangs = JenisBarang::orderBy('jenis_brg', 'asc')->get();
        $golonganBarangs = GolonganBarang::orderBy('golongan_brg', 'asc')->get();

        // Generate ID otomatis
        $newId = $this->generateId('A11', 'a11_dm_nama_brg');

        return view('nama-barang.create', compact('kategoriBarangs', 'jenisBarangs', 'golonganBarangs', 'newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_kode_a08' => 'required|exists:a08_dm_kategori_brg,id_kode',
            'id_kode_a09' => 'required|exists:a09_dm_jenis_brg,id_kode',
            'id_kode_a10' => 'required|exists:a10_dm_golongan_brg,id_kode',
            'nama_brg' => 'required|string|max:255',
            'no_kode_brg' => 'nullable|string|max:50|unique:a11_dm_nama_brg,no_kode_brg',
            'keterangan_brg' => 'nullable|string|max:65535',
        ], [
            'id_kode_a08.required' => 'Kategori barang harus dipilih',
            'id_kode_a08.exists' => 'Kategori barang yang dipilih tidak valid',
            'id_kode_a09.required' => 'Jenis barang harus dipilih',
            'id_kode_a09.exists' => 'Jenis barang yang dipilih tidak valid',
            'id_kode_a10.required' => 'Golongan barang harus dipilih',
            'id_kode_a10.exists' => 'Golongan barang yang dipilih tidak valid',
            'nama_brg.required' => 'Nama barang harus diisi',
            'no_kode_brg.unique' => 'Kode barang sudah digunakan, silakan gunakan kode yang lain',
        ]);

        // Generate ID jika tidak ada
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A11', 'a11_dm_nama_brg');
        } else {
            $id_kode = $request->id_kode;
        }

        $data = [
            'id_kode' => $id_kode,
            'no_kode_brg' => $request->no_kode_brg,
            'id_kode_a08' => $request->id_kode_a08,
            'id_kode_a09' => $request->id_kode_a09,
            'id_kode_a10' => $request->id_kode_a10,
            'nama_brg' => $request->nama_brg,
            'keterangan_brg' => $request->keterangan_brg,
            'created_by' => auth()->user()->id_kode ?? null,
        ];

        try {
            NamaBarang::create($data);

            return redirect()->route('nama-barang.index')
                ->with('success', 'Data Nama Barang berhasil ditambahkan');
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
        $namaBarang = NamaBarang::with(['kategoriBarang', 'jenisBarang', 'golonganbarang', 'creator', 'updater'])->findOrFail($id);

        return view('nama-barang.show', compact('namaBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $namaBarang = NamaBarang::findOrFail($id);
        $kategoriBarangs = KategoriBarang::orderBy('kategori_brg', 'asc')->get();
        $jenisBarangs = JenisBarang::orderBy('jenis_brg', 'asc')->get();
        $golonganBarangs = GolonganBarang::orderBy('golongan_brg', 'asc')->get();

        return view('nama-barang.edit', compact('namaBarang', 'kategoriBarangs', 'jenisBarangs', 'golonganBarangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $namaBarang = NamaBarang::findOrFail($id);

        $request->validate([
            'id_kode_a08' => 'required|exists:a08_dm_kategori_brg,id_kode',
            'id_kode_a09' => 'required|exists:a09_dm_jenis_brg,id_kode',
            'id_kode_a10' => 'required|exists:a10_dm_golongan_brg,id_kode',
            'nama_brg' => 'required|string|max:255',
            'no_kode_brg' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('a11_dm_nama_brg', 'no_kode_brg')->ignore($id, 'id_kode')
            ],
            'keterangan_brg' => 'nullable|string|max:65535',
        ], [
            'id_kode_a08.required' => 'Kategori barang harus dipilih',
            'id_kode_a08.exists' => 'Kategori barang yang dipilih tidak valid',
            'id_kode_a09.required' => 'Jenis barang harus dipilih',
            'id_kode_a09.exists' => 'Jenis barang yang dipilih tidak valid',
            'id_kode_a10.required' => 'Golongan barang harus dipilih',
            'id_kode_a10.exists' => 'Golongan barang yang dipilih tidak valid',
            'nama_brg.required' => 'Nama barang harus diisi',
            'no_kode_brg.unique' => 'Kode barang sudah digunakan, silakan gunakan kode yang lain',
        ]);

        $data = [
            'no_kode_brg' => $request->no_kode_brg,
            'id_kode_a08' => $request->id_kode_a08,
            'id_kode_a09' => $request->id_kode_a09,
            'id_kode_a10' => $request->id_kode_a10,
            'nama_brg' => $request->nama_brg,
            'keterangan_brg' => $request->keterangan_brg,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        try {
            $namaBarang->update($data);

            return redirect()->route('nama-barang.index')
                ->with('success', 'Data Nama Barang berhasil diperbarui');
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
        $namaBarang = NamaBarang::findOrFail($id);

        try {
            $namaBarang->delete();

            return redirect()->route('nama-barang.index')
                ->with('success', 'Data Nama Barang berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}