<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Guru/Bendahara who used personal funds');
            $table->foreignId('expense_category_id')->constrained('expense_categories')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('nominal', 12, 2);
            $table->text('uraian');
            $table->string('toko_vendor')->nullable();
            $table->string('lokasi')->nullable();
            $table->enum('status', ['Belum Diajukan', 'Diajukan', 'Disetujui', 'Dibayar', 'Ditolak'])->default('Belum Diajukan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
