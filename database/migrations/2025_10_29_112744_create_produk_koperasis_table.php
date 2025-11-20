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
        Schema::create('ms_produk_koperasi', function (Blueprint $table) {
            $table->id('ms_produk_koperasi_id');

            // relasi ke kategori koperasi
            $table->unsignedBigInteger('ms_kategori_produk_koperasi_id')->nullable();
            
            // data produk
            $table->string('kode_produk_koperasi')->unique();
            $table->string('nama_produk_koperasi');
            $table->string('satuan', 50)->nullable(); // contoh: pcs, pack, botol, dll
            $table->integer('stok')->default(0);
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);

            // status
            $table->enum('status_produk_koperasi', ['aktif', 'nonaktif'])->default('aktif');

            // opsional
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
        Schema::dropIfExists('produk_koperasis');
    }
};
