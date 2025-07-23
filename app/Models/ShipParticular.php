<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipParticular extends Model
{
    use HasFactory;

    protected $table = 'b02_ship_particular';

    protected $fillable = [
        'id_kode',
        'nama_kpl',
        'ship_particular_ket',
        'file_dok',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship with Kapal (Ship)
     */
    public function kapal()
    {
        return $this->belongsTo(Kapal::class, 'nama_kpl', 'id_kode');
    }

    /**
     * Relationship with User who created the record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_kode');
    }

    /**
     * Relationship with User who updated the record
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_kode');
    }
}