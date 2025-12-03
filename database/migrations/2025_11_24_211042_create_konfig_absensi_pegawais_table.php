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
        Schema::create('ms_absensi_pegawai_konfig', function (Blueprint $table) {
            $table->bigIncrements('ms_absensi_pegawai_konfig_id');

            $table->unsignedBigInteger('ms_jenjang_id')->index();

            $table->enum('hari', [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu',
                'minggu'
            ])->index();

            // Jam masuk
            $table->time('jam_masuk_awal')->nullable();
            $table->time('jam_masuk_akhir')->nullable();

            // Jam pulang
            $table->time('jam_pulang_awal')->nullable();
            $table->time('jam_pulang_akhir')->nullable();

            // Toleransi
            $table->integer('toleransi_masuk_menit')->default(0);
            $table->integer('toleransi_pulang_menit')->default(0);

            // Status aktif/non-aktif
            $table->tinyInteger('status_aktif')->default(1);

            // Keterangan tambahan
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
        Schema::dropIfExists('ms_absensi_pegawai_konfig');
    }
};
