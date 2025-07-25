<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'a09_dm_jenis_brg';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'jenis_brg',
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
}
