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
        Schema::create('sesi_absensis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sesi');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->time('toleransi_keterlambatan')->nullable(); // Contoh: 09:00 AM
            $table->time('maksimal_jam_pulang')->nullable(); // Contoh: 17:00 PM
            $table->boolean('aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_absensis');
    }
};
