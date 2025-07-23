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
        Schema::create('ms_transaksi', function (Blueprint $table) {
            $table->id('ms_transaksi_id');
            $table->unsignedBigInteger('ms_penempatan_siswa_id'); // Relasi ke siswa
            $table->unsignedBigInteger('ms_pengguna_id'); // Petugas yang memproses transaksi
            $table->timestamp('tanggal_transaksi');
            $table->decimal('infaq', 15, 2)->default(0); // Donasi tambahan, default 0
            $table->text('metode_pembayaran')->nullable(); // Catatan tambahan
            $table->text('deskripsi')->nullable(); // Catatan tambahan

            $table->timestamps(); // Menyimpan created_at dan updated_at
            $table->softDeletes(); // Untuk menghapus secara lunak (soft delete)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_transaksi');
    }
};
