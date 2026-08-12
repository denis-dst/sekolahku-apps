<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diploma extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'siswa_id',
        'no_ijazah',
        'tanggal_lulus',
        'file_pdf',
    ];

    protected $casts = [
        'tanggal_lulus' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
