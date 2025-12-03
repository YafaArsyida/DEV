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
        Schema::create('ms_settlement_kantin', function (Blueprint $table) {
            $table->bigIncrements('ms_settlement_kantin_id');

            $table->date('tanggal_settlement');
            $table->decimal('total_settlement', 14, 2)->default(0);

            $table->enum('metode_pembayaran', ['tunai', 'transfer']);
            $table->text('deskripsi')->nullable();

            // user yang melakukan settlement (TU)
            $table->unsignedBigInteger('ms_pengguna_id')->nullable();

            // relasi ke jurnal debit/kredit
            $table->unsignedBigInteger('akun_jurnal_debit_id')->nullable();
            $table->unsignedBigInteger('akun_jurnal_kredit_id')->nullable();

            $table->timestamps();

            // index
            $table->index('tanggal_settlement');
            $table->index('ms_pengguna_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_settlement_kantin');
    }
};
