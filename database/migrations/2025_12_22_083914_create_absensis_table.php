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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sesi_absensi_id')->nullable()->constrained();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->enum('status_masuk', ['tepat_waktu', 'terlambat', 'tidak_absen'])->default('tidak_absen');
            $table->enum('status_pulang', ['tepat_waktu', 'cepat', 'tidak_absen'])->default('tidak_absen');
            $table->string('bukti_pekerjaan')->nullable();
            $table->boolean('bukti_diupload')->default(false);
            $table->time('jam_upload_bukti')->nullable();
            $table->text('catatan')->nullable();
            $table->string('lokasi_masuk')->nullable();
            $table->string('lokasi_pulang')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
