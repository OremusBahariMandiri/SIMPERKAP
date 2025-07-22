<?php

namespace App\Http\Controllers;

use App\Models\JenisKapal;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;

class JenisKapalController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:jenis_kapal')->only('index', 'show');
        $this->middleware('check.access:jenis_kapal,tambah')->only('create', 'store');
        $this->middleware('check.access:jenis_kapal,ubah')->only('edit', 'update');
        $this->middleware('check.access:jenis_kapal,hapus')->only('destroy');
    }

    public function index()
    {
        $jenisKapal = JenisKapal::all();
        return view('jenis-kapal.index', compact('jenisKapal'));
    }

    public function create()
    {
        // Generar ID automático
        $newId = $this->generateId('A04', 'a04_dm_jenis_kpl');

        return view('jenis-kapal.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kpl' => 'required',
        ]);

        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A04', 'a04_dm_jenis_kpl');
        } else {
            $id_kode = $request->id_kode;
        }

        JenisKapal::create([
            'id_kode' => $id_kode,
            'jenis_kpl' => $request->jenis_kpl,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('jenis-kapal.index')
            ->with('success', 'Jenis Kapal berhasil dibuat.');
    }

    public function show(JenisKapal $jenisKapal)
    {
        return view('jenis-kapal.show', compact('jenisKapal'));
    }

    public function edit(JenisKapal $jenisKapal)
    {
        return view('jenis-kapal.edit', compact('jenisKapal'));
    }

    public function update(Request $request, JenisKapal $jenisKapal)
    {
        $request->validate([
            'jenis_kpl' => 'required',
        ]);

        $jenisKapal->update([
            'jenis_kpl' => $request->jenis_kpl,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('jenis-kapal.index')
            ->with('success', 'Jenis Kapal berhasil diperbarui.');
    }

    public function destroy(JenisKapal $jenisKapal)
    {
        $jenisKapal->delete();

        return redirect()->route('jenis-kapal.index')
            ->with('success', 'Jenis Kapal berhasil dihapus.');
    }
}