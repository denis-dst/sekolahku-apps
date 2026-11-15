<?php

namespace App\Models\Ingetin;

use Illuminate\Database\Eloquent\Model;

class IngetinHadith extends Model
{
    protected $connection = 'ingetin';
    protected $table = 'hadith';
    public $timestamps = false;

    protected $fillable = [
        'text',
        'narrator',
        'topic',
        'day_of_year',
        'is_active',
    ];

    protected $casts = [
        'day_of_year' => 'integer',
        'is_active' => 'integer',
    ];
}
