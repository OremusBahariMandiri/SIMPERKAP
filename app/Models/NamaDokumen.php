<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaDokumen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'a07_dm_nama_dok';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'id_kode_a06',
        'nama_dok',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the kategori dokumen for the nama dokumen.
     */
    public function kategoriDokumen()
    {
        return $this->belongsTo(KategoriDokumen::class, 'id_kode_a06', 'id_kode');
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
     * Get the dokumen kapal for the nama dokumen.
     */
    public function dokumenKapal()
    {
        return $this->hasMany(DokumenKapal::class, 'nama_dok', 'id_kode');
    }
}