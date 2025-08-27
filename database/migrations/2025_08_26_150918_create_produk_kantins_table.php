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
        Schema::create('ms_produk_kantin', function (Blueprint $table) {
            $table->id('ms_produk_kantin_id');
            $table->unsignedBigInteger('ms_jenjang_id');
            $table->unsignedBigInteger('ms_kategori_produk_kantin_id');
            $table->string('nama_produk_kantin', 150);
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->nullable();
            $table->string('satuan', 50)->default('pcs');
            $table->boolean('status')->default(1); // 1 aktif, 0 nonaktif
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
        Schema::dropIfExists('ms_produk_kantin');
    }
};
