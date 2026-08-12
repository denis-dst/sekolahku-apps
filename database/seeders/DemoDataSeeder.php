<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\School;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Rombel;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\ExpenseCategory;
use App\Models\TagihanSpp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Tenant
        $tenant = Tenant::create([
            'name' => 'Yayasan Pendidikan Nusantara',
            'code' => 'YPNUSANTARA',
            'subscription_tier' => 'pro',
            'status' => 'active',
            'subscription_expires_at' => now()->addYear(),
        ]);

        // 2. Create School
        $school = School::create([
            'tenant_id' => $tenant->id,
            'name' => 'TK Negeri Pembina 01 Sukajadi',
            'npsn' => '20109988',
            'jenjang' => 'PAUD/TK/RA',
            'address' => 'Jl. Pendidikan No. 45 Sukajadi',
            'phone' => '021-88997766',
            'email' => 'admin@tknpembina.sch.id',
            'logo' => null,
            'kop_header' => null,
            'qris_image' => 'demo/qris_sample.png',
            'bank_accounts' => [
                ['bank' => 'Bank Mandiri', 'account_number' => '123-00-9988776-5', 'account_name' => 'TK NEGERI PEMBINA 01'],
                ['bank' => 'Bank BCA', 'account_number' => '8890-123-456', 'account_name' => 'TK NEGERI PEMBINA 01'],
            ],
            'fonnte_token' => 'DEMO_FONNTE_TOKEN_123',
            'kepala_sekolah_nama' => 'Dra. Hj. Siti Rahmah, M.Pd.',
            'kepala_sekolah_nip' => '19750812 200003 2 001',
            'bendahara_nama' => 'Ahmadi, S.E.',
            'bendahara_nip' => '19820510 200801 1 004',
            'status' => 'active',
        ]);

        // 3. Create Users
        $adminUser = User::create([
            'tenant_id' => $tenant->id,
            'school_id' => $school->id,
            'name' => 'Superadmin SaaS',
            'email' => 'admin@sekolahku.id',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $adminUser->assignRole('Superadmin');

        $yayasanUser = User::create([
            'tenant_id' => $tenant->id,
            'school_id' => $school->id,
            'name' => 'Drs. H. M. Ridho (Yayasan)',
            'email' => 'yayasan@sekolahku.id',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $yayasanUser->assignRole('Yayasan Admin');

        $ksUser = User::create([
            'tenant_id' => $tenant->id,
            'school_id' => $school->id,
            'name' => 'Dra. Hj. Siti Rahmah, M.Pd.',
            'email' => 'headmaster@tknpembina.sch.id',
            'phone' => '081234567892',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $ksUser->assignRole('School Admin');

        $bendaharaUser = User::create([
            'tenant_id' => $tenant->id,
            'school_id' => $school->id,
            'name' => 'Ahmadi, S.E. (Bendahara)',
            'email' => 'bendahara@tknpembina.sch.id',
            'phone' => '081234567893',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $bendaharaUser->assignRole('Bendahara');

        $guruUser = User::create([
            'tenant_id' => $tenant->id,
            'school_id' => $school->id,
            'name' => 'Nurhayati, S.Pd. (Guru TK A)',
            'email' => 'guru@tknpembina.sch.id',
            'phone' => '081234567894',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $guruUser->assignRole('Guru');

        $ortuUser = User::create([
            'tenant_id' => $tenant->id,
            'school_id' => $school->id,
            'name' => 'Budi Santoso (Orang Tua)',
            'email' => 'ortu@tknpembina.sch.id',
            'phone' => '081234567895',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $ortuUser->assignRole('Orang Tua');

        $siswaUser = User::create([
            'tenant_id' => $tenant->id,
            'school_id' => $school->id,
            'name' => 'Muhammad Bintang Ramadhan',
            'email' => 'siswa@tknpembina.sch.id',
            'phone' => '081234567896',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $siswaUser->assignRole('Siswa');

        // 4. Create Tahun Ajaran & Rombel
        $ta = TahunAjaran::create([
            'school_id' => $school->id,
            'name' => '2025/2026',
            'semester' => '1',
            'is_active' => true,
            'start_date' => '2025-07-15',
            'end_date' => '2025-12-20',
        ]);

        $guru = Guru::create([
            'school_id' => $school->id,
            'user_id' => $guruUser->id,
            'nip' => '19880315 201502 2 003',
            'nuptk' => '889077665544',
            'nama_lengkap' => 'Nurhayati, S.Pd.',
            'gelar' => 'S.Pd.',
            'jenis_kelamin' => 'P',
            'no_hp' => '081234567894',
            'alamat' => 'Jl. Mawar No. 12',
        ]);

        $rombel = Rombel::create([
            'school_id' => $school->id,
            'tahun_ajaran_id' => $ta->id,
            'guru_id' => $guru->id,
            'nama_rombel' => 'TK-A1 (Bintang)',
            'tingkat' => 'A',
        ]);

        // 5. Create Siswa
        $siswa = Siswa::create([
            'school_id' => $school->id,
            'user_id' => $siswaUser->id,
            'rombel_id' => $rombel->id,
            'nisn' => '0011223344',
            'nik' => '320109988776655',
            'nama_lengkap' => 'Muhammad Bintang Ramadhan',
            'nama_panggilan' => 'Bintang',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2020-05-15',
            'nama_ortu' => 'Budi Santoso',
            'no_hp_ortu' => '081234567895',
            'alamat' => 'Jl. Melati No. 88 Sukajadi',
            'status' => 'Aktif',
        ]);

        // 6. Expense Categories
        $cat1 = ExpenseCategory::create([
            'school_id' => $school->id,
            'nama_kategori' => 'Alat Tulis & Bahan Pembelajaran',
            'kode_bosp' => 'BOSP-01',
            'keterangan' => 'Pengadaan Kertas, Krayon, Buku Gambar',
        ]);
        $cat2 = ExpenseCategory::create([
            'school_id' => $school->id,
            'nama_kategori' => 'Konsumsi & Operasional Harian',
            'kode_bosp' => 'BOSP-02',
            'keterangan' => 'Konsumsi Rapat, Snack Anak',
        ]);

        // 7. Initial Tagihan SPP
        TagihanSpp::create([
            'school_id' => $school->id,
            'siswa_id' => $siswa->id,
            'tahun_ajaran_id' => $ta->id,
            'bulan' => 'Agustus',
            'tahun' => 2026,
            'nominal' => 150000,
            'potongan' => 0,
            'total_tagihan' => 150000,
            'status' => 'Belum Lunas',
            'jatuh_tempo' => '2026-08-10',
        ]);
    }
}
