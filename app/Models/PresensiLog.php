<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'presensi_id',
        'user_id',
        'action',
        'notes',
        'ip_address',
    ];

    public function presensi()
    {
        return $this->belongsTo(Presensi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
