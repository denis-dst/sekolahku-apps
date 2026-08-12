<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        // Categorize permissions for clean visual matrix
        $permissionGroups = [
            'SaaS & Administrasi' => [
                'manage-saas' => 'Kelola SaaS, Paket & Langganan',
                'manage-roles' => 'Kelola Peran & Hak Akses (RBAC)',
                'manage-yayasan' => 'Kelola Unit Yayasan',
                'manage-school' => 'Kelola Profil & QRIS Sekolah',
                'manage-users' => 'Kelola Pengguna / Akun Login',
                'manage-master-data' => 'Kelola Master Data (Siswa, Guru, Rombel)',
            ],
            'Presensi & Kehadiran' => [
                'manage-presensi' => 'Presensi Kelas Morning (Guru)',
                'self-presensi' => 'Absen Mandiri Siswa (Portal)',
            ],
            'Keuangan & SPP' => [
                'manage-spp' => 'Kelola Tagihan SPP Massal',
                'upload-spp-bukti' => 'Upload Bukti Pembayaran SPP (Ortu)',
                'verify-spp-bukti' => 'Verifikasi Pembayaran SPP (Bendahara)',
                'manage-expenses' => 'Input Talangan Pribadi BOSP',
                'approve-expenses' => 'Persetujuan Claim Talangan BOSP',
                'manage-reimbursements' => 'Cairkan Reimburse & Cetak LPJ',
            ],
            'Akademik, E-Rapor & Anekdot' => [
                'manage-anekdot' => 'Catatan Anekdot Perkembangan',
                'manage-planning' => 'Perencanaan Pembelajaran',
                'manage-assessments' => 'Input Penilaian & Narasi',
                'manage-erapor' => 'Cetak E-Rapor PDF',
                'manage-supervisi' => 'Supervisi Pengajaran',
                'manage-assets' => 'Inventaris Aset Sekolah',
            ],
        ];

        return view('superadmin.roles.index', compact('roles', 'permissions', 'permissionGroups'));
    }

    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
        ]);

        // Sync permissions with the selected role
        $role->syncPermissions($request->permissions ?? []);

        // Forget cached permissions so changes take effect instantly
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Hak akses untuk peran (role) ' . $role->name . ' berhasil diperbarui!');
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $request->name, 'guard_name' => 'web']);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Peran (Role) baru "' . $request->name . '" berhasil ditambahkan!');
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name, 'guard_name' => 'web']);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Hak Akses (Permission) baru "' . $request->name . '" berhasil ditambahkan!');
    }
}
