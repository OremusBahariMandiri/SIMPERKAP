<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profile
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = Auth::user();

        // Cek password saat ini
        if (!Hash::check($request->current_password, $user->password_kry)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak benar.'
            ], 422);
        }

        // Update password
        $user->update([
            'password_kry' => Hash::make($request->password),
            'updated_by' => $user->nik_kry
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.'
        ]);
    }

    /**
     * Update profile user
     */
    // public function updateProfile(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'nama_kry' => 'required|string|max:255',
    //         'departemen_kry' => 'required|string|max:255',
    //         'jabatan_kry' => 'required|string|max:255',
    //         'wilker_kry' => 'required|string|max:255',
    //     ], [
    //         'nama_kry.required' => 'Nama karyawan wajib diisi.',
    //         'departemen_kry.required' => 'Departemen wajib diisi.',
    //         'jabatan_kry.required' => 'Jabatan wajib diisi.',
    //         'wilker_kry.required' => 'Wilayah kerja wajib diisi.',
    //     ]);

    //     $user->update([
    //         'nama_kry' => $request->nama_kry,
    //         'departemen_kry' => $request->departemen_kry,
    //         'jabatan_kry' => $request->jabatan_kry,
    //         'wilker_kry' => $request->wilker_kry,
    //         'updated_by' => $user->nik_kry
    //     ]);

    //     return redirect()->route('profile.index')
    //                     ->with('success', 'Profile berhasil diperbarui.');
    // }
}