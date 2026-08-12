<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'npsn',
        'jenjang',
        'address',
        'phone',
        'email',
        'logo',
        'kop_header',
        'qris_image',
        'bank_accounts',
        'fonnte_token',
        'kepala_sekolah_nama',
        'kepala_sekolah_nip',
        'bendahara_nama',
        'bendahara_nip',
        'status',
    ];

    protected $casts = [
        'bank_accounts' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }

    public function rombels()
    {
        return $this->hasMany(Rombel::class);
    }

    public function tahunAjarans()
    {
        return $this->hasMany(TahunAjaran::class);
    }

    public function expenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function subscriptionPlan()
    {
        return $this->tenant ? $this->tenant->subscriptionPlan() : null;
    }

    public function isSubscriptionActive(): bool
    {
        return $this->tenant ? $this->tenant->isSubscriptionActive() : true;
    }

    public function hasFeature(string $featureKey): bool
    {
        return $this->tenant ? $this->tenant->hasFeature($featureKey) : true;
    }

    public function canAddSiswa(): bool
    {
        if (!$this->isSubscriptionActive()) {
            return false;
        }

        $plan = $this->tenant?->subscriptionPlan;
        if (!$plan || $plan->max_siswas == 0) {
            return true; // Unlimited
        }

        return $this->siswas()->count() < $plan->max_siswas;
    }
}
