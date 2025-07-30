<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaBarang extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'a11_dm_nama_brg';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'no_kode_brg',
        'id_kode_a08',
        'id_kode_a09',
        'id_kode_a10',
        'nama_brg',
        'keterangan_brg',
        'created_by',
        'updated_by',
    ];

    public function kategoriBarang()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kode_a08', 'id_kode');
    }

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'id_kode_a09', 'id_kode');
    }

    public function golonganbarang()
    {
        return $this->belongsTo(GolonganBarang::class, 'id_kode_a10', 'id_kode');
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

    
}
