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
        Schema::create('ms_infaq', function (Blueprint $table) {
            $table->id('ms_infaq_id'); // Primary Key
            $table->unsignedBigInteger('ms_pengguna_id'); // Foreign key ke Pengguna
            $table->unsignedBigInteger('ms_jenjang_id'); // Foreign key ke Pengguna
            $table->unsignedBigInteger('ms_tahun_ajar_id'); // Foreign key ke Pengguna
            $table->string('nama_donatur')->nullable(); // Kolom untuk menyimpan foto kop
            $table->string('jumlah_infaq')->nullable();
            $table->string('keterangan')->nullable();
            $table->dateTime('tanggal_infaq')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_infaq');
    }
};
