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
        Schema::create('ms_transaksi_tabungan', function (Blueprint $table) {
            $table->id('ms_transaksi_tabungan_id');
            $table->enum('user_type', ['siswa', 'pegawai']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ms_penempatan_siswa_id')->nullable();
            $table->unsignedBigInteger('ms_pengguna_id')->nullable();
            $table->enum('jenis_transaksi', [
                'setoran',
                'penarikan',
            ]);
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal');
            $table->text('deskripsi')->nullable();

            $table->unsignedBigInteger('akuntansi_jurnal_detail_debit_id')->nullable();
            $table->unsignedBigInteger('akuntansi_jurnal_detail_kredit_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_transaksi_tabungan');
    }
};
