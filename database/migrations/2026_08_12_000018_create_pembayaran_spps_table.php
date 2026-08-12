<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_spps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_spp_id')->constrained('tagihan_spps')->onDelete('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->comment('User who uploaded or verified')->constrained('users')->onDelete('set null');
            $table->date('tanggal_bayar');
            $table->decimal('nominal_bayar', 12, 2);
            $table->enum('metode_pembayaran', ['Manual QRIS', 'Transfer Bank', 'Cash'])->default('Manual QRIS');
            $table->string('bukti_pembayaran')->nullable(); // Uploaded image/file of receipt/transfer
            $table->text('catatan_verifikasi')->nullable();
            $table->enum('status_verifikasi', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spps');
    }
};
