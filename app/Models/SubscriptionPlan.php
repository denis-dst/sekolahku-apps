<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'price',
        'billing_cycle',
        'max_siswas',
        'max_schools',
        'features',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'max_siswas' => 'integer',
        'max_schools' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public static function availableFeatures(): array
    {
        return [
            'presensi' => 'Presensi Dual Mode (Guru & Siswa)',
            'erapor' => 'E-Rapor Digital & PDF Rapor',
            'anekdot' => 'Catatan Anekdot Perkembangan',
            'spp_qris' => 'SPP & Payment Proof QRIS',
            'bendaharaku' => 'BendaharaKu & LPJ BOSP',
            'fonnte_wa' => 'WhatsApp Gateway (Fonnte)',
            'multi_school' => 'Manajemen Multi-Sekolah',
            'custom_branding' => 'Custom Branding & Logo',
        ];
    }

    public function hasFeature(string $featureKey): bool
    {
        if (!$this->features || !is_array($this->features)) {
            return false;
        }
        return in_array($featureKey, $this->features);
    }
}
