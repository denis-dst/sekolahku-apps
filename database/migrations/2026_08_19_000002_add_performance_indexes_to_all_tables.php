<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add optimized indexes across all relational tables.
     */
    public function up(): void
    {
        // 1. Tenants
        Schema::table('tenants', function (Blueprint $table) {
            $table->index(['subscription_status'], 'tenants_sub_status_idx');
            $table->index(['subscription_plan_id', 'subscription_status'], 'tenants_plan_status_idx');
        });

        // 2. Schools
        Schema::table('schools', function (Blueprint $table) {
            $table->index(['tenant_id', 'status'], 'schools_tenant_status_idx');
        });

        // 3. Users
        Schema::table('users', function (Blueprint $table) {
            $table->index(['tenant_id', 'school_id'], 'users_tenant_school_idx');
        });

        // 4. Tahun Ajarans
        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->index(['school_id', 'is_active'], 'tahun_ajarans_school_active_idx');
        });

        // 5. Rombels
        Schema::table('rombels', function (Blueprint $table) {
            $table->index(['school_id', 'tahun_ajaran_id'], 'rombels_school_ta_idx');
            $table->index(['school_id', 'guru_id'], 'rombels_school_guru_idx');
        });

        // 6. Gurus
        Schema::table('gurus', function (Blueprint $table) {
            $table->index(['school_id', 'user_id'], 'gurus_school_user_idx');
        });

        // 7. Siswas
        Schema::table('siswas', function (Blueprint $table) {
            $table->index(['school_id', 'status'], 'siswas_school_status_idx');
            $table->index(['school_id', 'rombel_id', 'status'], 'siswas_school_rombel_status_idx');
            $table->index(['school_id', 'user_id'], 'siswas_school_user_idx');
        });

        // 8. Presensis
        Schema::table('presensis', function (Blueprint $table) {
            $table->index(['school_id', 'tanggal', 'status'], 'presensis_school_tgl_status_idx');
            $table->index(['school_id', 'rombel_id', 'tanggal'], 'presensis_school_rombel_tgl_idx');
        });

        // 9. Tagihan SPPs
        Schema::table('tagihan_spps', function (Blueprint $table) {
            $table->index(['school_id', 'status'], 'tagihan_spps_school_status_idx');
            $table->index(['school_id', 'bulan', 'tahun'], 'tagihan_spps_school_bln_thn_idx');
            $table->index(['school_id', 'siswa_id', 'status'], 'tagihan_spps_school_siswa_status_idx');
        });

        // 10. Pembayaran SPPs
        Schema::table('pembayaran_spps', function (Blueprint $table) {
            $table->index(['school_id', 'status_verifikasi'], 'pembayaran_spps_school_status_idx');
            $table->index(['tagihan_spp_id', 'status_verifikasi'], 'pembayaran_spps_tagihan_status_idx');
        });

        // 11. Expenses
        Schema::table('expenses', function (Blueprint $table) {
            $table->index(['school_id', 'status'], 'expenses_school_status_idx');
            $table->index(['school_id', 'tanggal'], 'expenses_school_tanggal_idx');
            $table->index(['school_id', 'expense_category_id'], 'expenses_school_category_idx');
            $table->index(['school_id', 'user_id'], 'expenses_school_user_idx');
        });

        // 12. Assessments (E-Rapor)
        Schema::table('assessments', function (Blueprint $table) {
            $table->index(['school_id', 'rombel_id', 'siswa_id'], 'assessments_school_rombel_siswa_idx');
            $table->index(['school_id', 'siswa_id', 'jenis_penilaian'], 'assessments_school_siswa_jenis_idx');
        });

        // 13. Anekdots
        Schema::table('anekdots', function (Blueprint $table) {
            $table->index(['school_id', 'siswa_id', 'tanggal'], 'anekdots_school_siswa_tgl_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anekdots', fn(Blueprint $table) => $table->dropIndex('anekdots_school_siswa_tgl_idx'));
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropIndex('assessments_school_rombel_siswa_idx');
            $table->dropIndex('assessments_school_siswa_jenis_idx');
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('expenses_school_status_idx');
            $table->dropIndex('expenses_school_tanggal_idx');
            $table->dropIndex('expenses_school_category_idx');
            $table->dropIndex('expenses_school_user_idx');
        });
        Schema::table('pembayaran_spps', function (Blueprint $table) {
            $table->dropIndex('pembayaran_spps_school_status_idx');
            $table->dropIndex('pembayaran_spps_tagihan_status_idx');
        });
        Schema::table('tagihan_spps', function (Blueprint $table) {
            $table->dropIndex('tagihan_spps_school_status_idx');
            $table->dropIndex('tagihan_spps_school_bln_thn_idx');
            $table->dropIndex('tagihan_spps_school_siswa_status_idx');
        });
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropIndex('presensis_school_tgl_status_idx');
            $table->dropIndex('presensis_school_rombel_tgl_idx');
        });
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropIndex('siswas_school_status_idx');
            $table->dropIndex('siswas_school_rombel_status_idx');
            $table->dropIndex('siswas_school_user_idx');
        });
        Schema::table('gurus', fn(Blueprint $table) => $table->dropIndex('gurus_school_user_idx'));
        Schema::table('rombels', function (Blueprint $table) {
            $table->dropIndex('rombels_school_ta_idx');
            $table->dropIndex('rombels_school_guru_idx');
        });
        Schema::table('tahun_ajarans', fn(Blueprint $table) => $table->dropIndex('tahun_ajarans_school_active_idx'));
        Schema::table('users', fn(Blueprint $table) => $table->dropIndex('users_tenant_school_idx'));
        Schema::table('schools', fn(Blueprint $table) => $table->dropIndex('schools_tenant_status_idx'));
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex('tenants_sub_status_idx');
            $table->dropIndex('tenants_plan_status_idx');
        });
    }
};
