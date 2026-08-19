<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanaBosp extends Model
{
    use HasFactory;

    protected $table = 'dana_bosps';

    protected $fillable = [
        'school_id',
        'tahun',
        'periode',
        'nominal_cair',
        'tanggal_cair',
        'sumber_dana',
        'catatan',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'nominal_cair' => 'decimal:2',
        'tanggal_cair' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
