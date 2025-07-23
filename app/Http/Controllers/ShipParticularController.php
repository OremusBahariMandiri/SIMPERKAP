<?php

namespace App\Http\Controllers;

use App\Models\ShipParticular;
use App\Models\Kapal;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ShipParticularController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:ship_particular')->only('index', 'show');
        $this->middleware('check.access:ship_particular,tambah')->only('create', 'store');
        $this->middleware('check.access:ship_particular,ubah')->only('edit', 'update');
        $this->middleware('check.access:ship_particular,hapus')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipParticulars = ShipParticular::with(['kapal', 'creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ship-particular.index', compact('shipParticulars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kapal = Kapal::orderBy('nama_kpl', 'asc')->get();

        // Generate ID otomatis
        $newId = $this->generateId('B02', 'b02_ship_particular');

        return view('ship-particular.create', compact('kapal', 'newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kpl' => 'required|exists:a05_dm_kapal,id_kode',
            'ship_particular_ket' => 'nullable|string|max:65535',
            'file_dok' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png', // 5MB max
        ], [
            'nama_kpl.required' => 'Nama kapal harus dipilih',
            'nama_kpl.exists' => 'Nama kapal yang dipilih tidak valid',
            'file_dok.mimes' => 'File harus berformat: pdf, doc, docx, jpg, jpeg, png',
        ]);

        // Generate ID jika tidak ada
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('B02', 'b02_ship_particular');
        } else {
            $id_kode = $request->id_kode;
        }

        $data = [
            'id_kode' => $id_kode,
            'nama_kpl' => $request->nama_kpl,
            'ship_particular_ket' => $request->ship_particular_ket,
            'created_by' => auth()->user()->id_kode ?? null,
        ];

        // Handle file upload
        if ($request->hasFile('file_dok')) {
            $file = $request->file('file_dok');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('ship-particulars', $filename, 'public');
            $data['file_dok'] = $filePath;
        }

        try {
            ShipParticular::create($data);

            return redirect()->route('ship-particular.index')
                ->with('success', 'Data Ship Particular berhasil ditambahkan');
        } catch (\Exception $e) {
            // Delete uploaded file if database save fails
            if (isset($data['file_dok']) && Storage::disk('public')->exists($data['file_dok'])) {
                Storage::disk('public')->delete($data['file_dok']);
            }

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shipParticular = ShipParticular::with(['kapal', 'creator', 'updater'])->findOrFail($id);

        return view('ship-particular.show', compact('shipParticular'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $shipParticular = ShipParticular::findOrFail($id);
        $kapal = Kapal::orderBy('nama_kpl', 'asc')->get();

        return view('ship-particular.edit', compact('shipParticular', 'kapal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kpl' => 'required|exists:a05_dm_kapal,id_kode',
            'ship_particular_ket' => 'nullable|string|max:65535',
            'file_dok' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png', // 5MB max
        ], [
            'nama_kpl.required' => 'Nama kapal harus dipilih',
            'nama_kpl.exists' => 'Nama kapal yang dipilih tidak valid',
            'file_dok.mimes' => 'File harus berformat: pdf, doc, docx, jpg, jpeg, png',
        ]);

        $shipParticular = ShipParticular::findOrFail($id);

        $data = [
            'nama_kpl' => $request->nama_kpl,
            'ship_particular_ket' => $request->ship_particular_ket,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        $oldFile = $shipParticular->file_dok;

        // Handle file upload
        if ($request->hasFile('file_dok')) {
            $file = $request->file('file_dok');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('ship-particulars', $filename, 'public');
            $data['file_dok'] = $filePath;
        }

        try {
            $shipParticular->update($data);

            // Delete old file if new file is uploaded
            if (isset($data['file_dok']) && $oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            return redirect()->route('ship-particular.index')
                ->with('success', 'Data Ship Particular berhasil diperbarui');
        } catch (\Exception $e) {
            // Delete uploaded file if database save fails
            if (isset($data['file_dok']) && Storage::disk('public')->exists($data['file_dok'])) {
                Storage::disk('public')->delete($data['file_dok']);
            }

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shipParticular = ShipParticular::findOrFail($id);

        try {
            // Delete associated file
            if ($shipParticular->file_dok && Storage::disk('public')->exists($shipParticular->file_dok)) {
                Storage::disk('public')->delete($shipParticular->file_dok);
            }

            $shipParticular->delete();

            return redirect()->route('ship-particular.index')
                ->with('success', 'Data Ship Particular berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download file document
     */
    public function downloadFile($id)
    {
        $shipParticular = ShipParticular::findOrFail($id);

        if (!$shipParticular->file_dok || !Storage::disk('public')->exists($shipParticular->file_dok)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($shipParticular->file_dok);
    }

    public function viewFile($id)
    {
        $shipParticular = ShipParticular::findOrFail($id);

        if (!$shipParticular->file_dok || !Storage::disk('public')->exists($shipParticular->file_dok)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        $filePath = Storage::disk('public')->path($shipParticular->file_dok);
        $mimeType = Storage::disk('public')->mimeType($shipParticular->file_dok);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($shipParticular->file_dok) . '"'
        ]);
    }
}
