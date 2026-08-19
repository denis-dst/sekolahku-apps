<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dana_bosps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->integer('tahun');
            $table->string('periode')->default('Tahap 1 (Semester I)'); // Tahap 1, Tahap 2, Triwulan 1..4, Tahunan
            $table->decimal('nominal_cair', 15, 2)->default(0);
            $table->date('tanggal_cair')->nullable();
            $table->string('sumber_dana')->default('BOSP Reguler'); // BOSP Reguler, BOSP Kinerja, BOSP Daerah, etc.
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'tahun', 'periode'], 'dana_bosps_school_thn_periode_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dana_bosps');
    }
};
