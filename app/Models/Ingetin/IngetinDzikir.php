<?php

namespace App\Models\Ingetin;

use Illuminate\Database\Eloquent\Model;

class IngetinDzikir extends Model
{
    protected $connection = 'ingetin';
    protected $table = 'dzikir';
    public $timestamps = false;

    protected $fillable = [
        'category',
        'title',
        'arabic',
        'latin',
        'translation',
        'target_count',
        'benefit',
        'source',
        'order_index',
        'is_active',
    ];

    protected $casts = [
        'target_count' => 'integer',
        'order_index' => 'integer',
        'is_active' => 'integer',
    ];
}
