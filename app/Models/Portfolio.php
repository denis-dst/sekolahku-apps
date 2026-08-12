<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'siswa_id',
        'judul_karya',
        'deskripsi',
        'file_path',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
