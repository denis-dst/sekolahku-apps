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

    public function getFormattedNominalAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'Dibayar' => [
                'class' => 'bg-success text-white',
                'icon' => 'bi-check-circle-fill',
                'label' => 'Dibayar (Reimburse Selesai)',
            ],
            'Disetujui' => [
                'class' => 'bg-info text-dark',
                'icon' => 'bi-hand-thumbs-up-fill',
                'label' => 'Disetujui Kepala Sekolah',
            ],
            'Diajukan' => [
                'class' => 'bg-warning text-dark',
                'icon' => 'bi-hourglass-split',
                'label' => 'Diajukan',
            ],
            'Ditolak' => [
                'class' => 'bg-danger text-white',
                'icon' => 'bi-x-circle-fill',
                'label' => 'Ditolak',
            ],
            default => [
                'class' => 'bg-secondary text-white',
                'icon' => 'bi-clock-history',
                'label' => 'Belum Diajukan',
            ],
        };
    }
}
