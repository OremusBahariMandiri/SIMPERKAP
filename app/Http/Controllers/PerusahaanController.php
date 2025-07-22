<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:perusahaan')->only('index', 'show');
        $this->middleware('check.access:perusahaan,tambah')->only('create', 'store');
        $this->middleware('check.access:perusahaan,ubah')->only('edit', 'update');
        $this->middleware('check.access:perusahaan,hapus')->only('destroy');
    }

    public function index()
    {
        $perusahaan = Perusahaan::all();
        return view('perusahaan.index', compact('perusahaan'));
    }

    public function create()
    {
        // Generar ID automático
        $newId = $this->generateId('A03', 'a03_dm_perusahaan');

        return view('perusahaan.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prsh' => 'required',
            'alamat_prsh' => 'required',
        ]);

        // Generar ID si no está presente
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A03', 'a03_dm_perusahaan');
        } else {
            $id_kode = $request->id_kode;
        }

        Perusahaan::create([
            'id_kode' => $id_kode,
            'nama_prsh' => $request->nama_prsh,
            'alamat_prsh' => $request->alamat_prsh,
            'telp_prsh' => $request->telp_prsh,
            'telp_prsh2' => $request->telp_prsh2,
            'email_prsh' => $request->email_prsh,
            'email_prsh2' => $request->email_prsh2,
            'web_prsh' => $request->web_prsh,
            'tgl_berdiri' => $request->tgl_berdiri,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil dibuat.');
    }

    public function show(Perusahaan $perusahaan)
    {
        return view('perusahaan.show', compact('perusahaan'));
    }

    public function edit(Perusahaan $perusahaan)
    {
        return view('perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $request, Perusahaan $perusahaan)
    {
        $request->validate([
            'nama_prsh' => 'required',
            'alamat_prsh' => 'required',
        ]);

        $perusahaan->update([
            'nama_prsh' => $request->nama_prsh,
            'alamat_prsh' => $request->alamat_prsh,
            'telp_prsh' => $request->telp_prsh,
            'telp_prsh2' => $request->telp_prsh2,
            'email_prsh' => $request->email_prsh,
            'email_prsh2' => $request->email_prsh2,
            'web_prsh' => $request->web_prsh,
            'tgl_berdiri' => $request->tgl_berdiri,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil diperbarui.');
    }

    public function destroy(Perusahaan $perusahaan)
    {
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')
            ->with('success', 'Perusahaan berhasil dihapus.');
    }
}