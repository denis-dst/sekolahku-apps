<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisiDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisi_id',
        'aspek_penilaian',
        'skor',
        'catatan',
    ];

    public function supervisi()
    {
        return $this->belongsTo(Supervisi::class);
    }
}
