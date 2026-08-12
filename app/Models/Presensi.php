<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'rombel_id',
        'siswa_id',
        'tanggal',
        'status',
        'jam_masuk',
        'jam_pulang',
        'entry_type',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
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

    public function logs()
    {
        return $this->hasMany(PresensiLog::class);
    }
}
