<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserAccess;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'a01_dm_users';

    protected $fillable = [
        'id_kode',
        'nik_kry',
        'nama_kry',
        'departemen_kry',
        'jabatan_kry',
        'wilker_kry',
        'password_kry',
        'is_admin',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password_kry',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_kry;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'nik_kry';
    }

    public function userAccess()
    {
        return $this->hasMany(UserAccess::class, 'id_kode_a01', 'id_kode');
    }

    /**
     * Memeriksa apakah user memiliki akses ke menu tertentu
     *
     * @param string $menu Nama menu
     * @param string $action Nama aksi (tambah, ubah, hapus, dll)
     * @return bool
     */
    public function hasAccess($menu, $action = null)
    {
        // Admin memiliki semua akses
        if ($this->is_admin) {
            return true;
        }

        // Cek akses berdasarkan menu dan action
        foreach ($this->userAccess as $access) {
            if ($access->menu_acs == $menu) {
                if ($action === null) {
                    return true; // Hanya cek menu tanpa action
                }

                // Cek akses spesifik
                $actionField = $action . '_acs';
                return isset($access->$actionField) && $access->$actionField;
            }
        }

        return false;
    }
    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }
}
