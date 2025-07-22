<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenKapal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'b01_dokumen_kpl';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'no_reg',
        'nama_kpl',
        'kategori_dok',
        'nama_dok',
        'penerbit_dok',
        'validasi_dok',
        'tgl_terbit_dok',
        'tgl_berakhir_dok',
        'masa_berlaku',
        'tgl_peringatan',
        'masa_peringatan',
        'catatan',
        'file_dok',
        'status_dok',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_terbit_dok' => 'date',
        'tgl_berakhir_dok' => 'date',
        'tgl_peringatan' => 'date',
    ];

    /**
     * Get the kapal for the dokumen kapal.
     */
    public function kapal()
    {
        return $this->belongsTo(Kapal::class, 'nama_kpl', 'id_kode');
    }

    /**
     * Get the kategori dokumen for the dokumen kapal.
     */
    public function kategoriDokumen()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_dok', 'id_kode');
    }

    /**
     * Get the nama dokumen for the dokumen kapal.
     */
    public function namaDokumen()
    {
        return $this->belongsTo(NamaDokumen::class, 'nama_dok', 'id_kode');
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