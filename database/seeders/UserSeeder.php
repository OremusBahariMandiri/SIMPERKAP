<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserAccess;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat user admin
        $admin = User::create([
            'id_kode' => 'ADM001',
            'nik_kry' => '1234567890',
            'nama_kry' => 'Administrator',
            'departemen_kry' => 'IT',
            'jabatan_kry' => 'System Administrator',
            'wilker_kry' => 'Pusat',
            'password_kry' => Hash::make('admin123'),
            'is_admin' => true,
            'created_by' => 'SYSTEM',
        ]);

        // Buat user regular dengan akses penuh ke modul perusahaan dan jenis kapal
        $user1 = User::create([
            'id_kode' => 'USR001',
            'nik_kry' => '2345678901',
            'nama_kry' => 'Operator Data',
            'departemen_kry' => 'Operasional',
            'jabatan_kry' => 'Staff Data Entry',
            'wilker_kry' => 'Cabang Jakarta',
            'password_kry' => Hash::make('user123'),
            'is_admin' => false,
            'created_by' => 'ADM001',
        ]);

        // Buat akses untuk user1
        $this->createAccess($user1->id_kode, 'perusahaan', true, true, true, true, true, true);
        $this->createAccess($user1->id_kode, 'jenis_kapal', true, true, true, true, true, true);

        // Buat user readonly untuk dokumen kapal
        $user2 = User::create([
            'id_kode' => 'USR002',
            'nik_kry' => '3456789012',
            'nama_kry' => 'Petugas Monitoring',
            'departemen_kry' => 'Pengawasan',
            'jabatan_kry' => 'Staff Monitoring',
            'wilker_kry' => 'Cabang Surabaya',
            'password_kry' => Hash::make('user123'),
            'is_admin' => false,
            'created_by' => 'ADM001',
        ]);

        // Buat akses untuk user2 (hanya lihat dan download)
        $this->createAccess($user2->id_kode, 'dokumen_kapal', false, false, false, true, true, true);

        // Buat user dengan akses ke semua modul tapi tanpa hak hapus
        $user3 = User::create([
            'id_kode' => 'USR003',
            'nik_kry' => '4567890123',
            'nama_kry' => 'Supervisor Data',
            'departemen_kry' => 'Operasional',
            'jabatan_kry' => 'Supervisor',
            'wilker_kry' => 'Cabang Makassar',
            'password_kry' => Hash::make('user123'),
            'is_admin' => false,
            'created_by' => 'ADM001',
        ]);

        // Buat akses untuk user3 (semua akses kecuali hapus)
        $modules = [
            'pengguna',
            'perusahaan',
            'jenis_kapal',
            'kapal',
            'kategori_dokumen',
            'nama_dokumen',
            'dokumen_kapal'
        ];

        foreach ($modules as $module) {
            $this->createAccess($user3->id_kode, $module, true, true, false, true, true, true);
        }
    }

    /**
     * Helper untuk membuat user access
     */
    private function createAccess($userId, $menu, $tambah, $ubah, $hapus, $download, $detail, $monitoring)
    {
        return UserAccess::create([
            'id_kode_a01' => $userId,
            'menu_acs' => $menu,
            'tambah_acs' => $tambah,
            'ubah_acs' => $ubah,
            'hapus_acs' => $hapus,
            'download_acs' => $download,
            'detail_acs' => $detail,
            'monitoring_acs' => $monitoring,
            'created_by' => 'SYSTEM',
        ]);
    }
}