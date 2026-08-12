<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetRiwayat extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id',
        'user_id',
        'tanggal_perbaikan',
        'deskripsi_kerusakan',
        'tindakan',
        'biaya',
        'status',
    ];

    protected $casts = [
        'tanggal_perbaikan' => 'date',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
