<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GolonganBarang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'a10_dm_golongan_brg';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'golongan_brg',
        'ket_brg',
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
     * Get the barang for the golongan barang.
     * Note: Uncomment and adjust this if you have a related Barang model
     */
    // public function barang()
    // {
    //     return $this->hasMany(Barang::class, 'id_kode_a08', 'id_kode');
    // }
}