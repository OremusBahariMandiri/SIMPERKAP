<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\Perusahaan;
use App\Models\JenisKapal;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;

class KapalController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:kapal')->only('index', 'show');
        $this->middleware('check.access:kapal,tambah')->only('create', 'store');
        $this->middleware('check.access:kapal,ubah')->only('edit', 'update');
        $this->middleware('check.access:kapal,hapus')->only('destroy');
    }

    public function index()
    {
        $kapal = Kapal::with(['perusahaan', 'jenisKapal'])->get();
        return view('kapal.index', compact('kapal'));
    }

    public function create()
    {
        $perusahaan = Perusahaan::all();
        $jenisKapal = JenisKapal::all();

        // Generar ID automático
        $newId = $this->generateId('A05', 'a05_dm_kapal');

        return view('kapal.create', compact('perusahaan', 'jenisKapal', 'newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prsh' => 'required|exists:a03_dm_perusahaan,id_kode',
            'nama_kpl' => 'required',
            'jenis_kpl' => 'required|exists:a04_dm_jenis_kpl,id_kode',
        ]);

        // Generar ID si no está presente
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A05', 'a05_dm_kapal');
        } else {
            $id_kode = $request->id_kode;
        }

        Kapal::create([
            'id_kode' => $id_kode,
            'nama_prsh' => $request->nama_prsh,
            'no_imo' => $request->no_imo,
            'nama_kpl' => $request->nama_kpl,
            'jenis_kpl' => $request->jenis_kpl,
            'tonase_kpl' => $request->tonase_kpl,
            'tanda_panggilan_kpl' => $request->tanda_panggilan_kpl,
            'awak_kpl' => $request->awak_kpl,
            'penumpang_kpl' => $request->penumpang_kpl,
            'bendera_kpl' => $request->bendera_kpl,
            'thn_pbtn_kpl' => $request->thn_pbtn_kpl,
            'asal_pbtn_kpl' => $request->asal_pbtn_kpl,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('kapal.index')
            ->with('success', 'Kapal berhasil dibuat.');
    }

    public function show(Kapal $kapal)
    {
        $kapal->load(['perusahaan', 'jenisKapal']);
        return view('kapal.show', compact('kapal'));
    }

    public function edit(Kapal $kapal)
    {
        $perusahaan = Perusahaan::all();
        $jenisKapal = JenisKapal::all();
        return view('kapal.edit', compact('kapal', 'perusahaan', 'jenisKapal'));
    }

    public function update(Request $request, Kapal $kapal)
    {
        $request->validate([
            'nama_prsh' => 'required|exists:a03_dm_perusahaan,id_kode',
            'nama_kpl' => 'required',
            'jenis_kpl' => 'required|exists:a04_dm_jenis_kpl,id_kode',
        ]);

        $kapal->update([
            'nama_prsh' => $request->nama_prsh,
            'no_imo' => $request->no_imo,
            'nama_kpl' => $request->nama_kpl,
            'jenis_kpl' => $request->jenis_kpl,
            'tonase_kpl' => $request->tonase_kpl,
            'tanda_panggilan_kpl' => $request->tanda_panggilan_kpl,
            'awak_kpl' => $request->awak_kpl,
            'penumpang_kpl' => $request->penumpang_kpl,
            'bendera_kpl' => $request->bendera_kpl,
            'thn_pbtn_kpl' => $request->thn_pbtn_kpl,
            'asal_pbtn_kpl' => $request->asal_pbtn_kpl,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('kapal.index')
            ->with('success', 'Kapal berhasil diperbarui.');
    }

    public function destroy(Kapal $kapal)
    {
        $kapal->delete();

        return redirect()->route('kapal.index')
            ->with('success', 'Kapal berhasil dihapus.');
    }
}