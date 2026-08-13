<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_description',
        'contact_email',
        'contact_phone',
        'contact_address',
        'contact_maps_embed',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
