<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'expense_id',
        'user_id',
        'nominal_reimburse',
        'tanggal_pencairan',
        'metode_transfer',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pencairan' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
