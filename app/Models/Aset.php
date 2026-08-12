<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'kode_aset',
        'nama_aset',
        'kategori',
        'sumber_dana',
        'tanggal_pengadaan',
        'lokasi',
        'kondisi',
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function riwayats()
    {
        return $this->hasMany(AsetRiwayat::class);
    }
}
