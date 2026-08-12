<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'nip',
        'nuptk',
        'nama_lengkap',
        'gelar',
        'jenis_kelamin',
        'no_hp',
        'alamat',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rombels()
    {
        return $this->hasMany(Rombel::class, 'guru_id');
    }

    public function anekdots()
    {
        return $this->hasMany(Anekdot::class);
    }

    public function plannings()
    {
        return $this->hasMany(Planning::class);
    }
}
