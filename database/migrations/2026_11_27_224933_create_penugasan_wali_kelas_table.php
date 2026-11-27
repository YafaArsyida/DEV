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
        Schema::create('ms_penugasan_wali_kelas', function (Blueprint $table) {
            $table->id('ms_penugasan_wali_kelas_id');

            $table->unsignedBigInteger('ms_pegawai_id');

            $table->unsignedBigInteger('ms_kelas_id');
            $table->unsignedBigInteger('ms_tahun_ajar_id');

            $table->string('status')->default('Aktif');
            $table->text('deskripsi')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Satu kelas hanya memiliki satu wali kelas dalam satu tahun ajaran.
            $table->unique(
                ['ms_kelas_id', 'ms_tahun_ajar_id'],
                'unique_wali_kelas_per_tahun'
            );

            $table->index('ms_pegawai_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_penugasan_wali_kelas');
    }
};
