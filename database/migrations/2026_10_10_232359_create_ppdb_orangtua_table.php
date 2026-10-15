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
        Schema::create('ppdb_orangtua', function (Blueprint $table) {
            $table->id('ppdb_orangtua_id');

            $table->unsignedBigInteger('ppdb_pendaftaran_id')->unique();

            // Data ayah
            $table->string('nama_ayah', 150)->nullable();
            $table->string('nik_ayah', 20)->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('telepon_ayah', 25)->nullable();

            // Data ibu
            $table->string('nama_ibu', 150)->nullable();
            $table->string('nik_ibu', 20)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->string('telepon_ibu', 25)->nullable();

            // Data wali jika ada
            $table->string('nama_wali', 150)->nullable();
            $table->string('hubungan_wali', 50)->nullable();
            $table->string('telepon_wali', 25)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_orangtua');
    }
};
