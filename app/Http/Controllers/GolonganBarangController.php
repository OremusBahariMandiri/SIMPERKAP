<?php

namespace App\Http\Controllers;

use App\Models\GolonganBarang;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;

class GolonganBarangController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:golongan_barang')->only('index', 'show');
        $this->middleware('check.access:golongan_barang,tambah')->only('create', 'store');
        $this->middleware('check.access:golongan_barang,ubah')->only('edit', 'update');
        $this->middleware('check.access:golongan_barang,hapus')->only('destroy');
    }

    public function index()
    {
        $golonganBarang = GolonganBarang::all();
        return view('golongan-barang.index', compact('golonganBarang'));
    }

    public function create()
    {
        // Generate ID otomatis
        $newId = $this->generateId('A10', 'a10_dm_golongan_brg');

        return view('golongan-barang.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'golongan_brg' => 'required',
            'ket_brg' => 'nullable',
        ]);

        // Generate ID jika tidak ada
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A10', 'a10_dm_golongan_brg');
        } else {
            $id_kode = $request->id_kode;
        }

        GolonganBarang::create([
            'id_kode' => $id_kode,
            'golongan_brg' => $request->golongan_brg,
            'ket_brg' => $request->ket_brg,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('golongan-barang.index')
            ->with('success', 'Golongan Barang berhasil dibuat.');
    }

    public function show($id)
    {
        $golonganBarang = GolonganBarang::findOrFail($id);
        return view('golongan-barang.show', compact('golonganBarang'));
    }

    public function edit($id)
    {
        $golonganBarang = GolonganBarang::findOrFail($id);
        return view('golongan-barang.edit', compact('golonganBarang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'golongan_brg' => 'required',
            'ket_brg' => 'nullable',
        ]);

        $golonganBarang = GolonganBarang::findOrFail($id);
        $golonganBarang->update([
            'golongan_brg' => $request->golongan_brg,
            'ket_brg' => $request->ket_brg,
            'updated_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('golongan-barang.index')
            ->with('success', 'Golongan Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $golonganBarang = GolonganBarang::findOrFail($id);
        $golonganBarang->delete();

        return redirect()->route('golongan-barang.index')
            ->with('success', 'Golongan Barang berhasil dihapus.');
    }
}