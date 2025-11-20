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
        Schema::create('ms_kategori_produk_koperasi', function (Blueprint $table) {
            $table->id('ms_kategori_produk_koperasi_id');
            $table->string('nama_kategori_produk_koperasi');
            $table->enum('status_kategori_produk_koperasi', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('deskripsi')->nullable();

            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_kategori_produk_koperasi');
    }
};
