<?php

namespace App\Http\Controllers;

use App\Models\KategoriDokumen;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;

class KategoriDokumenController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:kategori_dokumen')->only('index', 'show');
        $this->middleware('check.access:kategori_dokumen,tambah')->only('create', 'store');
        $this->middleware('check.access:kategori_dokumen,ubah')->only('edit', 'update');
        $this->middleware('check.access:kategori_dokumen,hapus')->only('destroy');
    }

    public function index()
    {
        $kategoriDokumen = KategoriDokumen::all();
        return view('kategori-dokumen.index', compact('kategoriDokumen'));
    }

    public function create()
    {
        // Generate ID otomatis
        $newId = $this->generateId('A06', 'a06_dm_kategori_dok');

        return view('kategori-dokumen.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_dok' => 'required',
        ]);

        // Generate ID jika tidak ada
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A06', 'a06_dm_kategori_dok');
        } else {
            $id_kode = $request->id_kode;
        }

        KategoriDokumen::create([
            'id_kode' => $id_kode,
            'kategori_dok' => $request->kategori_dok,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('kategori-dokumen.index')
            ->with('success', 'Kategori Dokumen berhasil dibuat.');
    }

    public function show($id)
    {
        $kategoriDokumen = KategoriDokumen::findOrFail($id);
        return view('kategori-dokumen.show', compact('kategoriDokumen'));
    }

    public function edit($id)
    {
        $kategoriDokumen = KategoriDokumen::findOrFail($id);
        return view('kategori-dokumen.edit', compact('kategoriDokumen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_dok' => 'required',
        ]);

        $kategoriDokumen = KategoriDokumen::findOrFail($id);
        $kategoriDokumen->update([
            'kategori_dok' => $request->kategori_dok,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('kategori-dokumen.index')
            ->with('success', 'Kategori Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategoriDokumen = KategoriDokumen::findOrFail($id);
        $kategoriDokumen->delete();

        return redirect()->route('kategori-dokumen.index')
            ->with('success', 'Kategori Dokumen berhasil dihapus.');
    }
}