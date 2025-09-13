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
        Schema::create('ms_keranjang_smartcanteen', function (Blueprint $table) {
            $table->bigIncrements('ms_keranjang_smartcanteen_id'); // PK
            $table->enum('user_type', ['siswa', 'pegawai']);       // tipe pemilik keranjang
            $table->unsignedBigInteger('user_id');                 // id siswa/pegawai
            $table->unsignedBigInteger('ms_produk_kantin_id');     // produk kantin
            $table->unsignedBigInteger('ms_pengguna_id');          // pengguna sistem (kasir/pegawai yang input)
            $table->integer('jumlah_produk')->default(1);          // jumlah produk

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_keranjang_smartcanteen');
    }
};
