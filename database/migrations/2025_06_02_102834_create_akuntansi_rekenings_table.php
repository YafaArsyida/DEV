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
        Schema::create('akuntansi_rekening', function (Blueprint $table) {
            $table->id('akuntansi_rekening_id'); // Primary Key
            $table->unsignedBigInteger('akuntansi_kelompok_rekening_id'); // FK ke kelompok
            $table->string('kode_rekening', 20)->unique(); // Misal: 1.01, 4.02
            $table->string('nama_rekening', 100); // Nama akun, seperti: Kas, Pendapatan SPP
            $table->enum('posisi_normal', ['debit', 'kredit']); // Posisi default akun
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Opsional tapi disarankan untuk master table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuntansi_rekening');
    }
};
