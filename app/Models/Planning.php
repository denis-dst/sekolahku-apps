<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'guru_id',
        'rombel_id',
        'judul',
        'minggu_ke',
        'tanggal_mulai',
        'tanggal_selesai',
        'capaian_pembelajaran',
        'tujuan_pembelajaran',
        'kegiatan',
        'evaluasi',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
