<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisKapal extends Model
{
    use HasFactory;

    protected $table = 'b03_Inventaris_Kpl';

    protected $fillable = [
        'id_kode',
        'id_kode_05',
        'id_kode_11',
        'id_kode_08',
        'id_kode_09',
        'id_kode_10',
        'no_kode_brg',
        'no_kode_brg_subtitusi',
        'tipe_brg',
        'spesifikasi_brg',
    'satuan_brg',
        'merek_brg',
        'supplier_brg',
        'lokasi_brg',
        'keterangan_brg',
        'tgl_pengadaan_brg',
        'no_pengadaan_brg',
        'stock_awal',
        'stock_masuk',
        'stock_keluar',
        'stock_akhir',
        'stock_limit',
        'file_dok',
        'created_by',
        'updated_by'
    ];

    public function kapal()
    {
        return $this->belongsTo(Kapal::class, 'id_kode_a05', 'id_kode');
    }

    public function namaBarang()
    {
        return $this->belongsTo(namaBarang::class, 'id_kode_a11', 'id_kode');
    }

    public function kategoriBarang()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kode_a08', 'id_kode');
    }

    public function jenisBarang()
    {
        return $this->belongsTo(jenisBarang::class, 'id_kode_a09', 'id_kode');
    }

    public function golonganBarang()
    {
        return $this->belongsTo(golonganBarang::class, 'id_kode_a10', 'id_kode');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_kode');
    }


    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_kode');
    }
}
