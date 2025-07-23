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
        Schema::create('dt_transaksi', function (Blueprint $table) {
            $table->id('dt_transaksi_id');
            $table->unsignedBigInteger('ms_transaksi_id'); // Relasi ke transaksi utama
            $table->unsignedBigInteger('ms_tagihan_id'); // Relasi ke tagihan
            $table->decimal('jumlah_bayar', 15, 2); // Nominal yang dibayar untuk tagihan ini
            $table->timestamps(); // Menyimpan created_at dan updated_at
            $table->softDeletes(); // Untuk menghapus secara lunak (soft delete)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_transaksi');
    }
};
