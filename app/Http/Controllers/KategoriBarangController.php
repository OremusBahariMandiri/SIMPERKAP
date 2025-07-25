<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use App\Models\GolonganBarang;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:kategori_barang')->only('index', 'show');
        $this->middleware('check.access:kategori_barang,tambah')->only('create', 'store');
        $this->middleware('check.access:kategori_barang,ubah')->only('edit', 'update');
        $this->middleware('check.access:kategori_barang,hapus')->only('destroy');
    }

    public function index()
    {
        $kategoriBarang = KategoriBarang::all();
        return view('kategori-barang.index', compact('kategoriBarang'));
    }

    public function create()
    {
        $kategoriBarang = KategoriBarang::all();

        // Generate ID otomatis
        $newId = $this->generateId('A08', 'a08_dm_kategori_brg');

        return view('kategori-barang.create', compact('kategoriBarang', 'newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_brg' => 'required',
            'ket_brg' => 'nullable',
        ]);

        // Generate ID jika tidak ada
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A08', 'a08_dm_kategori_brg');
        } else {
            $id_kode = $request->id_kode;
        }

        KategoriBarang::create([
            'id_kode' => $id_kode,
            'kategori_brg' => $request->kategori_brg,
            'ket_brg' => $request->ket_brg,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil dibuat.');
    }

    public function show($id)
    {
        $kategoriBarang = KategoriBarang::findOrFail($id);
        return view('kategori-barang.show', compact('kategoriBarang'));
    }

    public function edit($id)
    {
        $kategoriBarang = KategoriBarang::findOrFail($id);
        return view('kategori-barang.edit', compact('kategoriBarang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_brg' => 'required',
            'ket_brg' => 'nullable',
        ]);

        $kategoriBarang = KategoriBarang::findOrFail($id);
        $kategoriBarang->update([
            'kategori_brg' => $request->kategori_brg,
            'ket_brg' => $request->ket_brg,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategoriBarang = KategoriBarang::findOrFail($id);
        $kategoriBarang->delete();

        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil dihapus.');
    }
}