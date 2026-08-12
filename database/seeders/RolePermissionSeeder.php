<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage-saas',
            'manage-yayasan',
            'manage-school',
            'manage-users',
            'manage-master-data',
            'manage-presensi',
            'self-presensi',
            'manage-anekdot',
            'manage-planning',
            'manage-assessments',
            'manage-erapor',
            'manage-spp',
            'upload-spp-bukti',
            'verify-spp-bukti',
            'manage-expenses',
            'approve-expenses',
            'manage-reimbursements',
            'manage-supervisi',
            'manage-assets',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Roles
        $superadmin = Role::findOrCreate('Superadmin', 'web');
        $superadmin->givePermissionTo(Permission::all());

        $yayasan = Role::findOrCreate('Yayasan Admin', 'web');
        $yayasan->givePermissionTo(['manage-yayasan', 'manage-school', 'manage-supervisi']);

        $schoolAdmin = Role::findOrCreate('School Admin', 'web');
        $schoolAdmin->givePermissionTo(['manage-school', 'manage-users', 'manage-master-data', 'manage-presensi', 'manage-anekdot', 'manage-planning', 'manage-assessments', 'manage-erapor', 'manage-spp', 'verify-spp-bukti', 'approve-expenses', 'manage-supervisi', 'manage-assets']);

        $bendahara = Role::findOrCreate('Bendahara', 'web');
        $bendahara->givePermissionTo(['manage-spp', 'verify-spp-bukti', 'manage-expenses', 'approve-expenses', 'manage-reimbursements']);

        $guru = Role::findOrCreate('Guru', 'web');
        $guru->givePermissionTo(['manage-presensi', 'manage-anekdot', 'manage-planning', 'manage-assessments', 'manage-erapor', 'manage-expenses']);

        $ortu = Role::findOrCreate('Orang Tua', 'web');
        $ortu->givePermissionTo(['upload-spp-bukti']);

        $siswa = Role::findOrCreate('Siswa', 'web');
        $siswa->givePermissionTo(['self-presensi']);
    }
}
