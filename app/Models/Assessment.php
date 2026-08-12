<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'rombel_id',
        'siswa_id',
        'guru_id',
        'mata_pelajaran',
        'jenis_penilaian',
        'nilai_angka',
        'capaian_narasi',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
