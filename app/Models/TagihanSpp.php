<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanSpp extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'siswa_id',
        'tahun_ajaran_id',
        'bulan',
        'tahun',
        'nominal',
        'potongan',
        'total_tagihan',
        'status',
        'jatuh_tempo',
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(PembayaranSpp::class);
    }
}
