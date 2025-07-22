<?php

namespace App\Http\Controllers;

use App\Models\NamaDokumen;
use App\Models\KategoriDokumen;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;

class NamaDokumenController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:nama_dokumen')->only('index', 'show');
        $this->middleware('check.access:nama_dokumen,tambah')->only('create', 'store');
        $this->middleware('check.access:nama_dokumen,ubah')->only('edit', 'update');
        $this->middleware('check.access:nama_dokumen,hapus')->only('destroy');
    }

    public function index()
    {
        $namaDokumen = NamaDokumen::with('kategoriDokumen')->get();
        return view('nama-dokumen.index', compact('namaDokumen'));
    }

    public function create()
    {
        $kategoriDokumen = KategoriDokumen::all();

        // Generate ID otomatis
        $newId = $this->generateId('A07', 'a07_dm_nama_dok');

        return view('nama-dokumen.create', compact('kategoriDokumen', 'newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kode_a06' => 'required|exists:a06_dm_kategori_dok,id_kode',
            'nama_dok' => 'required',
        ]);

        // Generate ID jika tidak ada
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A07', 'a07_dm_nama_dok');
        } else {
            $id_kode = $request->id_kode;
        }

        NamaDokumen::create([
            'id_kode' => $id_kode,
            'id_kode_a06' => $request->id_kode_a06,
            'nama_dok' => $request->nama_dok,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('nama-dokumen.index')
            ->with('success', 'Nama Dokumen berhasil dibuat.');
    }

    public function show($id)
    {
        $namaDokumen = NamaDokumen::with('kategoriDokumen')->findOrFail($id);
        return view('nama-dokumen.show', compact('namaDokumen'));
    }

    public function edit($id)
    {
        $namaDokumen = NamaDokumen::findOrFail($id);
        $kategoriDokumen = KategoriDokumen::all();
        return view('nama-dokumen.edit', compact('namaDokumen', 'kategoriDokumen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kode_a06' => 'required|exists:a06_dm_kategori_dok,id_kode',
            'nama_dok' => 'required',
        ]);

        $namaDokumen = NamaDokumen::findOrFail($id);
        $namaDokumen->update([
            'id_kode_a06' => $request->id_kode_a06,
            'nama_dok' => $request->nama_dok,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('nama-dokumen.index')
            ->with('success', 'Nama Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $namaDokumen = NamaDokumen::findOrFail($id);
        $namaDokumen->delete();

        return redirect()->route('nama-dokumen.index')
            ->with('success', 'Nama Dokumen berhasil dihapus.');
    }
}