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
        Schema::create('ms_transaksi_kantin', function (Blueprint $table) {
            $table->id('ms_transaksi_kantin_id');
            $table->enum('user_type', ['siswa', 'pegawai']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ms_pengguna_id');
            $table->dateTime('tanggal_transaksi');
            $table->decimal('total_transaksi', 12, 2)->default(0);
            $table->text('metode_pembayaran')->nullable(); // Catatan tambahan
            $table->string('deskripsi')->nullable();

            $table->unsignedBigInteger('akuntansi_jurnal_detail_debit_id')->nullable();
            $table->unsignedBigInteger('akuntansi_jurnal_detail_kredit_id')->nullable();
           
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_transaksi_kantin');
    }
};
