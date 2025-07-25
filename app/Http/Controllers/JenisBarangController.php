<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:jenis_barang')->only('index', 'show');
        $this->middleware('check.access:jenis_barang,tambah')->only('create', 'store');
        $this->middleware('check.access:jenis_barang,ubah')->only('edit', 'update');
        $this->middleware('check.access:jenis_barang,hapus')->only('destroy');
    }

    public function index()
    {
        $jenisBarang = JenisBarang::all();
        return view('jenis-barang.index', compact('jenisBarang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisBarang = JenisBarang::all();

        // Generate ID otomatis
        $newId = $this->generateId('A09', 'a09_dm_jenis_brg');

        return view('jenis-barang.create', compact('jenisBarang', 'newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_brg' => 'required',
            'ket_brg' => 'nullable',
        ]);

        // Generate ID jika tidak ada
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A09', 'a09_dm_jenis_brg');
        } else {
            $id_kode = $request->id_kode;
        }

        JenisBarang::create([
            'id_kode' => $id_kode,
            'jenis_brg' => $request->jenis_brg,
            'ket_brg' => $request->ket_brg,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('jenis-barang.index')
            ->with('success', 'Jenis Barang berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jenisBarang = JenisBarang::findOrFail($id);
        return view('jenis-barang.show', compact('jenisBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenisBarang = JenisBarang::findOrFail($id);
        return view('jenis-barang.edit', compact('jenisBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_brg' => 'required',
            'ket_brg' => 'nullable',
        ]);

        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->update([
            'kategori_brg' => $request->kategori_brg,
            'ket_brg' => $request->ket_brg,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('jenis-barang.index')
            ->with('success', 'Jenis Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->delete();

        return redirect()->route('jenis-barang.index')
            ->with('success', 'Jenis Barang berhasil dihapus.');
    }
}
