<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'supervisor_id',
        'supervisee_id',
        'tanggal',
        'jenis',
        'total_skor',
        'catatan_umpan_balik',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function supervisee()
    {
        return $this->belongsTo(User::class, 'supervisee_id');
    }

    public function details()
    {
        return $this->hasMany(SupervisiDetail::class);
    }
}
