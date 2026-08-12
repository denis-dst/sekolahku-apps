<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anekdot extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'siswa_id',
        'guru_id',
        'tanggal',
        'peristiwa',
        'analisis_capaian',
        'umpan_balik',
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

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function lampirans()
    {
        return $this->hasMany(AnekdotLampiran::class);
    }
}
