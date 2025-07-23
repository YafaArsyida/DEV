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
        Schema::create('akuntansi_kelompok_rekening', function (Blueprint $table) {
            $table->id('akuntansi_kelompok_rekening_id'); // Primary Key
            $table->string('nama_kelompok_rekening', 100); // Nama kelompok, misal: Aset, Pendapatan
            $table->text('deskripsi')->nullable(); // Optional deskripsi
            $table->softDeletes(); // Soft delete
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuntansi_kelompok_rekening');
    }
};
