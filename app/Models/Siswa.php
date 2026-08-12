<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'rombel_id',
        'nisn',
        'nik',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ortu',
        'no_hp_ortu',
        'alamat',
        'foto',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    public function anekdots()
    {
        return $this->hasMany(Anekdot::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function tagihanSpps()
    {
        return $this->hasMany(TagihanSpp::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }
}
