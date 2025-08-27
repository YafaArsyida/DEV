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
        Schema::create('ms_kategori_produk_kantin', function (Blueprint $table) {
            $table->id('ms_kategori_produk_kantin_id');
            $table->unsignedBigInteger('ms_jenjang_id');
            $table->string('nama_kategori_produk_kantin', 150);
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
        Schema::dropIfExists('ms_kategori_produk_kantin');
    }
};
