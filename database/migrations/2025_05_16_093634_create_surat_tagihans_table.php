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
        Schema::create('ms_surat_tagihan', function (Blueprint $table) {
            $table->id('ms_surat_tagihan_id'); // Primary Key
            $table->string('foto_kop')->nullable(); // Kolom untuk menyimpan foto kop
            $table->string('tempat_tanggal')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('hal')->nullable();
            $table->text('salam_pembuka')->nullable();
            $table->text('pembuka')->nullable();
            $table->text('isi')->nullable();
            $table->text('rincian')->nullable();
            $table->text('panduan')->nullable();
            $table->text('instruksi_1')->nullable();
            $table->text('instruksi_2')->nullable();
            $table->text('instruksi_3')->nullable();
            $table->text('instruksi_4')->nullable();
            $table->text('instruksi_5')->nullable();
            $table->text('penutup')->nullable();
            $table->text('salam_penutup')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('tanda_tangan')->nullable();
            $table->string('nama_petugas')->nullable();
            $table->string('nomor_petugas')->nullable();
            $table->text('catatan_1')->nullable();
            $table->text('catatan_2')->nullable();
            $table->text('catatan_3')->nullable();
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_surat_tagihan');
    }
};
