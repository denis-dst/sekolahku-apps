<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'user_id',
        'status_sebelum',
        'status_sesudah',
        'catatan',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
