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
        Schema::create('ms_beban_pemeliharaan', function (Blueprint $table) {
            $table->id('ms_beban_pemeliharaan_id');
            $table->unsignedBigInteger('ms_pengguna_id');
            $table->unsignedBigInteger('ms_jenjang_id');
            $table->unsignedBigInteger('ms_tahun_ajar_id');
            $table->enum('jenis_beban_pemeliharaan', ['gedung', 'kendaraan', 'peralatan', 'lingkungan', 'lain lain']);
            $table->decimal('nominal', 15, 2);
            $table->enum('metode_pembayaran', ['tunai', 'bank']);
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('akuntansi_jurnal_detail_debit_id');
            $table->unsignedBigInteger('akuntansi_jurnal_detail_kredit_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_beban_pemeliharaan');
    }
};
