<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'a05_dm_kapal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'nama_prsh',
        'no_imo',
        'nama_kpl',
        'jenis_kpl',
        'tonase_kpl',
        'tanda_panggilan_kpl',
        'awak_kpl',
        'penumpang_kpl',
        'bendera_kpl',
        'thn_pbtn_kpl',
        'asal_pbtn_kpl',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'awak_kpl' => 'integer',
        'penumpang_kpl' => 'integer',
        'thn_pbtn_kpl' => 'integer',
    ];

    /**
     * Get the perusahaan for the kapal.
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'nama_prsh', 'id_kode');
    }

    /**
     * Get the jenis kapal for the kapal.
     */
    public function jenisKapal()
    {
        return $this->belongsTo(JenisKapal::class, 'jenis_kpl', 'id_kode');
    }

    /**
     * Get the creator user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_kode');
    }

    /**
     * Get the updater user.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_kode');
    }

    /**
     * Get the dokumen for the kapal.
     */
    public function dokumen()
    {
        return $this->hasMany(DokumenKapal::class, 'nama_kpl', 'id_kode');
    }
}