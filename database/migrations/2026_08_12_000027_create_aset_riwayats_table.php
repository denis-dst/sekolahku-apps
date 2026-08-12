<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset_riwayats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained('asets')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('tanggal_perbaikan');
            $table->text('deskripsi_kerusakan');
            $table->text('tindakan')->nullable();
            $table->decimal('biaya', 12, 2)->default(0);
            $table->enum('status', ['Proses', 'Selesai'])->default('Selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset_riwayats');
    }
};
