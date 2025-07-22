<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Http\Request;

class UserAccessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:hak_akses')->only('edit', 'show');
        $this->middleware('check.access:hak_akses,ubah')->only('update');
    }

    /**
     * Show access rights for a user
     */
    public function show(User $user)
    {
        // Redirect to edit as they have the same view
        return redirect()->route('user-access.edit', $user->id);
    }

    /**
     * Show the form for editing user access.
     */
    public function edit(User $user)
    {
        $menuList = [
            'pengguna' => 'Pengguna',
            'hak_akses' => 'Hak Akses',
            'perusahaan' => 'Perusahaan',
            'jenis_kapal' => 'Jenis Kapal',
            'kapal' => 'Kapal',
            'kategori_dokumen' => 'Kategori Dokumen',
            'nama_dokumen' => 'Nama Dokumen',
            'dokumen_kapal' => 'Dokumen Kapal'
        ];

        // Prepare user access data
        $userAccess = [];
        foreach ($user->userAccess as $access) {
            $userAccess[$access->menu_acs] = [
                'tambah' => $access->tambah_acs,
                'ubah' => $access->ubah_acs,
                'hapus' => $access->hapus_acs,
                'download' => $access->download_acs,
                'detail' => $access->detail_acs,
                'monitoring' => $access->monitoring_acs,
            ];
        }

        return view('user-access.edit', compact('user', 'menuList', 'userAccess'));
    }

    /**
     * Update the user access in storage.
     */
    public function update(Request $request, User $user)
    {
        // Update admin status if changed
        if ($user->is_admin != $request->has('is_admin')) {
            $user->update([
                'is_admin' => $request->has('is_admin') ? 1 : 0,
                'updated_by' => auth()->user()->id_kode ?? null,
            ]);
        }

        // If user is now an admin, delete all access records as they're not needed
        if ($request->has('is_admin')) {
            UserAccess::where('id_kode_a01', $user->id_kode)->delete();
            return redirect()->route('user-access.edit', $user->id)
                ->with('success', 'Pengguna berhasil diubah menjadi Administrator dengan akses penuh.');
        }

        // Delete existing access
        UserAccess::where('id_kode_a01', $user->id_kode)->delete();

        // Create new access if menu_access is provided
        if ($request->has('menu_access')) {
            foreach ($request->menu_access as $menu => $permissions) {
                UserAccess::create([
                    'id_kode_a01' => $user->id_kode,
                    'menu_acs' => $menu,
                    'tambah_acs' => isset($permissions['tambah']),
                    'ubah_acs' => isset($permissions['ubah']),
                    'hapus_acs' => isset($permissions['hapus']),
                    'download_acs' => isset($permissions['download']),
                    'detail_acs' => isset($permissions['detail']),
                    'monitoring_acs' => isset($permissions['monitoring']),
                    'created_by' => auth()->user()->id_kode ?? null,
                ]);
            }
        }

        return redirect()->route('user-access.edit', $user->id)
            ->with('success', 'Hak akses pengguna berhasil diperbarui.');
    }
}