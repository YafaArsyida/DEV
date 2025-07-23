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
        Schema::create('ms_dana_bos', function (Blueprint $table) {
            $table->id('ms_dana_bos_id'); // Primary Key
            $table->unsignedBigInteger('ms_pengguna_id'); // Foreign key ke tabel Pengguna
            $table->unsignedBigInteger('ms_jenjang_id'); // Foreign key ke tabel Jenjang
            $table->unsignedBigInteger('ms_tahun_ajar_id'); // Foreign key ke tabel Tahun Ajar
            $table->string('jenis_dana')->nullable(); // Kolom untuk jenis dana BOS
            $table->decimal('nominal', 15, 2)->nullable(); // Kolom untuk nominal (dengan 2 desimal)
            $table->string('metode_pembayaran')->nullable(); // Kolom untuk metode pembayaran
            $table->date('tanggal')->nullable(); // Kolom untuk tanggal transaksi
            $table->text('deskripsi')->nullable(); // Kolom untuk deskripsi
            $table->unsignedBigInteger('akuntansi_jurnal_detail_debit_id'); // Foreign key ke tabel jurnal debit
            $table->unsignedBigInteger('akuntansi_jurnal_detail_kredit_id'); // Foreign key ke tabel jurnal kredit
            $table->softDeletes(); // Soft delete
            $table->timestamps(); // Created_at dan Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_dana_bos');
    }
};
