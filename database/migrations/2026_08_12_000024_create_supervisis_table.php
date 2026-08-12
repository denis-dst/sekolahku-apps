<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('supervisor_id')->comment('KS or Yayasan User')->constrained('users')->onDelete('cascade');
            $table->foreignId('supervisee_id')->comment('Teacher or KS User')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis', ['Akademik', 'Manajerial'])->default('Akademik');
            $table->decimal('total_skor', 5, 2)->default(0);
            $table->text('catatan_umpan_balik')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisis');
    }
};
