<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $free = SubscriptionPlan::updateOrCreate(
            ['code' => 'free'],
            [
                'name' => 'Free / Starter',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'max_siswas' => 50,
                'max_schools' => 1,
                'features' => ['presensi', 'erapor', 'anekdot'],
                'description' => 'Paket dasar gratis untuk sekolah kecil / PAUD. Maksimal 50 siswa.',
                'is_active' => true,
            ]
        );

        $pro = SubscriptionPlan::updateOrCreate(
            ['code' => 'pro'],
            [
                'name' => 'Professional (Pro)',
                'price' => 199000,
                'billing_cycle' => 'monthly',
                'max_siswas' => 0, // Unlimited
                'max_schools' => 1,
                'features' => ['presensi', 'erapor', 'anekdot', 'spp_qris', 'bendaharaku', 'fonnte_wa'],
                'description' => 'Fitur lengkap untuk sekolah menengah (SPP QRIS, BendaharaKu, WA Fonnte, Unlimited Siswa).',
                'is_active' => true,
            ]
        );

        $enterprise = SubscriptionPlan::updateOrCreate(
            ['code' => 'enterprise'],
            [
                'name' => 'Enterprise (Yayasan)',
                'price' => 499000,
                'billing_cycle' => 'monthly',
                'max_siswas' => 0, // Unlimited
                'max_schools' => 5,
                'features' => ['presensi', 'erapor', 'anekdot', 'spp_qris', 'bendaharaku', 'fonnte_wa', 'multi_school', 'custom_branding'],
                'description' => 'Solusi lengkap untuk Yayasan dengan hingga 5 unit sekolah.',
                'is_active' => true,
            ]
        );

        // Assign default Pro plan to existing Demo Tenant
        $demoTenant = Tenant::first();
        if ($demoTenant) {
            $demoTenant->update([
                'subscription_plan_id' => $pro->id,
                'subscription_tier' => 'pro',
                'subscription_status' => 'active',
                'subscribed_at' => now(),
                'subscription_expires_at' => now()->addYear(),
                'notes' => 'Langganan Demo Pro 1 Tahun disetujui Superadmin.',
            ]);
        }
    }
}
