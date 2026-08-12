<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranSpp extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagihan_spp_id',
        'school_id',
        'siswa_id',
        'user_id',
        'tanggal_bayar',
        'nominal_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'catatan_verifikasi',
        'status_verifikasi',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function tagihanSpp()
    {
        return $this->belongsTo(TagihanSpp::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
