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
        Schema::create('ms_absensi_pegawai', function (Blueprint $table) {
            $table->id('ms_absensi_pegawai_id');

            $table->unsignedBigInteger('ms_pegawai_id')->nullable();
            $table->unsignedBigInteger('ms_pengguna_id')->nullable();

            $table->string('kode_kartu')->nullable();

            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            $table->string('status_masuk', 50)->nullable();
            $table->string('status_pulang', 50)->nullable();

            $table->text('deskripsi')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_absensi_pegawai');
    }
};
