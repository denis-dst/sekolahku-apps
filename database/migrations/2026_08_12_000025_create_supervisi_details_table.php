<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisi_id')->constrained('supervisis')->onDelete('cascade');
            $table->string('aspek_penilaian');
            $table->integer('skor')->default(4); // 1-5
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisi_details');
    }
};
