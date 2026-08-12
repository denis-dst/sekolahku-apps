<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NarrativeBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'elemen',
        'rentang_nilai',
        'template_narasi',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
