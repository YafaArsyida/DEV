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
        Schema::create('akuntansi_jurnal', function (Blueprint $table) {
            $table->id('akuntansi_jurnal_id');

            // Identitas jurnal
            $table->string('nomor_jurnal', 50)->unique();
            $table->dateTime('tanggal_transaksi');

            // Informasi transaksi
            $table->string('deskripsi')->nullable();

            // Pengguna yang membuat jurnal
            $table->unsignedBigInteger('ms_pengguna_id')->nullable();

            // Konteks akademik
            $table->unsignedBigInteger('ms_tahun_ajaran_id')->nullable();
            $table->unsignedBigInteger('ms_jenjang_id')->nullable();

            // Departemen
            $table->string('ms_departemen_id', 50)->nullable();

            // Status jurnal
            $table->enum('status', [
                'active',
                'canceled'
            ])->default('active');

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('tanggal_transaksi');
            $table->index('ms_pengguna_id');
            $table->index('ms_tahun_ajaran_id');
            $table->index('ms_jenjang_id');
            $table->index('ms_departemen_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuntansi_jurnal');
    }
};
