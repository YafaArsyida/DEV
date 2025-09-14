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
        Schema::create('dt_transaksi_kantin', function (Blueprint $table) {
            $table->id('dt_transaksi_kantin_id');
            $table->unsignedBigInteger('ms_transaksi_kantin_id');
            $table->unsignedBigInteger('ms_produk_kantin_id');
            $table->integer('jumlah_produk')->default(1);
            $table->decimal('jumlah_bayar', 12, 2)->default(0);
            $table->string('deskripsi')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_transaksi_kantin');
    }
};
