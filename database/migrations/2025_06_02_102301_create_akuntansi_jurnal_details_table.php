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
            $table->id('akuntansi_jurnal_detail_id');

            // Relasi ke jurnal header
            $table->unsignedBigInteger('akuntansi_jurnal_id');

            // Rekening akuntansi
            $table->string('kode_rekening', 50);

            // Posisi transaksi
            $table->enum('posisi', [
                'debit',
                'kredit'
            ]);

            // Nominal transaksi
            $table->decimal('nominal', 15, 2);

            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('akuntansi_jurnal_id')
                ->references('akuntansi_jurnal_id')
                ->on('akuntansi_jurnal')
                ->cascadeOnDelete();

            // Index
            $table->index('kode_rekening');
            $table->index('posisi');
            $table->index([
                'akuntansi_jurnal_id',
                'kode_rekening'
            ]);
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
