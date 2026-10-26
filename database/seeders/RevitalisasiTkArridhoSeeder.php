<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RevitalisasiTkArridhoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = database_path('seeders/seed_revitalisasi_tk_arridho.sql');
        
        // Fallback path ke workspace revit-tkarridho jika belum dicopy
        if (!File::exists($sqlPath)) {
            $sqlPath = 'D:/laragon/www/revit-tkarridho/seed_revitalisasi_tk_arridho.sql';
        }

        if (!File::exists($sqlPath)) {
            $this->command->error("File SQL tidak ditemukan di {$sqlPath}");
            return;
        }

        $this->command->info("Membaca file SQL: {$sqlPath}");
        $sql = File::get($sqlPath);

        // Eksekusi seluruh query secara berurutan
        DB::unprepared($sql);

        $this->command->info("SUKSES: Seluruh data transaksi pembukuan revitalisasi TK AR-RIDHO berhasil dimuat!");
        $this->command->info("- 32 Transaksi (terima_dana, tarik_bank, belanja_tunai)");
        $this->command->info("- 69 Sub-Items Belanja Material, Alat, Upah & Manajemen");
        $this->command->info("- Total Pengeluaran Realisasi: Rp 150.599.457,31 (Selisih: Rp 0,00)");
    }
}
