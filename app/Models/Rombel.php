<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'tahun_ajaran_id',
        'guru_id',
        'nama_rombel',
        'tingkat',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
