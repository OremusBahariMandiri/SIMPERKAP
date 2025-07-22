<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriDokumen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'a06_dm_kategori_dok';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'kategori_dok',
        'created_by',
        'updated_by',
    ];

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
     * Get the nama dokumen for the kategori dokumen.
     */
    public function namaDokumen()
    {
        return $this->hasMany(NamaDokumen::class, 'id_kode_a06', 'id_kode');
    }

    /**
     * Get the dokumen kapal for the kategori dokumen.
     */
    public function dokumenKapal()
    {
        return $this->hasMany(DokumenKapal::class, 'kategori_dok', 'id_kode');
    }
}