<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'nama_kategori',
        'kode_bosp',
        'keterangan',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
