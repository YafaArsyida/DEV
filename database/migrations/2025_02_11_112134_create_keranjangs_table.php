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
        Schema::create('ms_keranjang', function (Blueprint $table) {
            $table->id('ms_keranjang_id');
            $table->unsignedBigInteger('ms_penempatan_siswa_id'); // Foreign key ke Penempatan Siswa
            $table->unsignedBigInteger('ms_tagihan_id'); // Foreign key ke Tagihan
            $table->unsignedBigInteger('ms_pengguna_id'); // Foreign key ke Pengguna
            $table->decimal('jumlah_bayar', 15, 2); // Nominal pembayaran (bisa dicicil)
            $table->timestamp('tanggal_dibayar')->nullable(); // Tanggal pembayaran
            $table->enum('status', ['Masih Dicicil', 'Lunas'])->default('Masih Dicicil'); // Status pembayaran
            $table->text('deskripsi')->nullable(); // Catatan tambahan

            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_keranjang');
    }
};
