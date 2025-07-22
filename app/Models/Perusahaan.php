<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'a03_dm_perusahaan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode',
        'nama_prsh',
        'alamat_prsh',
        'telp_prsh',
        'telp_prsh2',
        'email_prsh',
        'email_prsh2',
        'web_prsh',
        'tgl_berdiri',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_berdiri' => 'date',
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
     * Get the kapal for the perusahaan.
     */
    public function kapal()
    {
        return $this->hasMany(Kapal::class, 'nama_prsh', 'id_kode');
    }
}