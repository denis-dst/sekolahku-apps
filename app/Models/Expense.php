<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'expense_category_id',
        'tanggal',
        'nominal',
        'uraian',
        'toko_vendor',
        'lokasi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function receipts()
    {
        return $this->hasMany(ExpenseReceipt::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(ExpenseStatusHistory::class);
    }

    public function reimbursement()
    {
        return $this->hasOne(Reimbursement::class);
    }
}
