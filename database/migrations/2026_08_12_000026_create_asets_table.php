<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('kode_aset')->nullable();
            $table->string('nama_aset');
            $table->string('kategori')->default('Elektronik'); // Elektronik, Mebel, Bangunan, Kendaraan
            $table->enum('sumber_dana', ['BOSP', 'Yayasan', 'Hibah', 'Lainnya'])->default('BOSP');
            $table->date('tanggal_pengadaan')->nullable();
            $table->string('lokasi')->nullable();
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
