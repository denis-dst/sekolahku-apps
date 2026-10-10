<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('revitalisasi_proyeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('nama_kegiatan');
            $table->integer('tahun_anggaran')->default(2026);
            $table->string('nomor_pks')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('atas_nama_rekening')->nullable();
            $table->decimal('pagu_anggaran', 15, 2)->default(0);
            $table->string('lokasi_surat')->default('Jakarta');
            $table->date('tanggal_tutup_buku')->nullable();
            $table->string('penanggung_jawab_nama')->nullable();
            $table->string('penanggung_jawab_nip')->nullable();
            $table->string('ketua_p2sp_nama')->nullable();
            $table->string('ketua_p2sp_nip')->nullable();
            $table->string('bendahara_nama')->nullable();
            $table->string('bendahara_nip')->nullable();
            $table->string('pengawas_nama')->nullable();
            $table->string('pengawas_nip')->nullable();
            $table->string('status', 30)->default('Aktif');
            $table->timestamps();

            $table->index(['school_id', 'tahun_anggaran'], 'rev_proyek_school_thn_idx');
        });

        Schema::create('revitalisasi_transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('revitalisasi_proyek_id')->constrained('revitalisasi_proyeks')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nomor_bukti');
            $table->date('tanggal');
            $table->string('jenis_transaksi', 30); // terima_dana, tarik_bank, belanja_tunai, belanja_transfer
            $table->text('uraian');
            $table->string('jenis_biaya', 50)->nullable(); // Bahan, Alat, Upah, Manajemen
            $table->string('penerima_vendor')->nullable();
            $table->decimal('nominal', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('file_bukti')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index(['school_id', 'revitalisasi_proyek_id', 'tanggal'], 'rev_trx_sc_pr_tgl_idx');
            $table->index(['jenis_transaksi', 'jenis_biaya'], 'rev_trx_jenis_idx');
        });

        Schema::create('revitalisasi_transaksi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revitalisasi_transaksi_id')->constrained('revitalisasi_transaksis')->cascadeOnDelete();
            $table->string('uraian_item');
            $table->string('jenis_biaya', 50); // Bahan, Alat, Upah, Manajemen
            $table->decimal('volume', 10, 2)->nullable();
            $table->string('satuan', 50)->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index(['revitalisasi_transaksi_id', 'jenis_biaya'], 'rev_item_trx_jns_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revitalisasi_transaksi_items');
        Schema::dropIfExists('revitalisasi_transaksis');
        Schema::dropIfExists('revitalisasi_proyeks');
    }
};
