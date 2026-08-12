<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('narrative_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('elemen'); // Nilai Agama, Jati Diri, Literasi & STEAM, Bahasa, Matematika
            $table->string('rentang_nilai')->default('Sangat Baik'); // Sangat Baik, Baik, Cukup, Perlu Bimbingan
            $table->text('template_narasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('narrative_banks');
    }
};
