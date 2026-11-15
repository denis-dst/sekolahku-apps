<?php

namespace App\Models\Ingetin;

use Illuminate\Database\Eloquent\Model;

class IngetinDonationStat extends Model
{
    protected $table = 'ingetin_donation_stats';

    protected $fillable = [
        'id',
        'total_donors',
        'total_amount_text',
        'total_programs',
        'note',
    ];

    protected $casts = [
        'total_donors' => 'integer',
        'total_programs' => 'integer',
    ];
}
