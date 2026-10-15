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
        Schema::create('ppdb_berkas', function (Blueprint $table) {
            $table->id('ppdb_berkas_id');

            $table->unsignedBigInteger('ppdb_pendaftaran_id');

            // Jenis berkas, misalnya kk, akta_lahir, ijazah
            $table->string('jenis_berkas', 50);

            $table->string('nama_file', 255)->nullable();
            $table->string('path_file', 500)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('ukuran_file')->nullable();

            // pending, valid, invalid
            $table->string('status_verifikasi', 20)->default('pending');

            $table->text('catatan_verifikasi')->nullable();

            // ID petugas yang memverifikasi
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['ppdb_pendaftaran_id', 'jenis_berkas'],
                'ppdb_berkas_pendaftaran_jenis_index'
            );

            $table->index('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_berkas');
    }
};
