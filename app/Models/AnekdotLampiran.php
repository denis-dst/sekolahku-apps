<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnekdotLampiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'anekdot_id',
        'file_path',
        'file_type',
    ];

    public function anekdot()
    {
        return $this->belongsTo(Anekdot::class);
    }
}
