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
        Schema::create('ms_aktifitas_pengguna', function (Blueprint $table) {
            $table->id('ms_aktifitas_pengguna_id'); // Primary key
            $table->unsignedBigInteger('ms_pengguna_id'); // ID pengguna
            $table->string('tipe_aksi'); // Tipe aksi, contoh: create, update, delete
            $table->string('tipe_tabel'); // Entitas yang dimodifikasi, contoh: Siswa, Produk
            $table->unsignedBigInteger('id_tabel')->nullable(); // ID entitas yang dimodifikasi
            $table->text('deskripsi')->nullable(); // Penjelasan aktivitas
            $table->string('ip_pengguna', 45)->nullable(); // IP Address pengguna
            $table->text('perangkat_pengguna')->nullable(); // Informasi perangkat pengguna

            $table->softDeletes(); // Soft delete
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_aktifitas_pengguna');
    }
};
