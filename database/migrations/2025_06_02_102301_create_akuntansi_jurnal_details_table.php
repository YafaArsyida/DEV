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
        Schema::create('akuntansi_jurnal_detail', function (Blueprint $table) {
            $table->id('akuntansi_jurnal_detail_id'); // Primary Key

            // Relasi ke transaksi di aplikasi (misal pembayaran SPP)
            $table->unsignedBigInteger('ms_transaksi_id'); // FK ke tabel transaksi aplikasi

            // Relasi ke akun yang digunakan (misal: Kas, Pendapatan SPP)
            $table->unsignedBigInteger('akuntansi_rekening_id');

            // Posisi entri jurnal (bukan posisi normal akun)
            $table->enum('posisi', ['debit', 'kredit']);

            // Nilai transaksi
            $table->decimal('nominal', 15, 2);

            // Keterangan tambahan
            $table->text('deskripsi')->nullable();

            // Metadata pendukung laporan
            $table->date('tanggal_transaksi'); // Tanggal transaksi
            $table->unsignedBigInteger('tahun_ajaran_id')->nullable(); // Opsional
            $table->unsignedBigInteger('jenjang_id')->nullable();      // Opsional

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuntansi_jurnal_detail');
    }
};
