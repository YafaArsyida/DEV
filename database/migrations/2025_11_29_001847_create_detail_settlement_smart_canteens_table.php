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
        Schema::create('dt_settlement_kantin', function (Blueprint $table) {
            $table->bigIncrements('dt_settlement_kantin_id');

            // parent settlement
            $table->unsignedBigInteger('ms_settlement_kantin_id');

            // transaksi kantin yang di-settle
            $table->unsignedBigInteger('ms_transaksi_kantin_id');

            // nominal transaksi yg termasuk settlement
            $table->decimal('nominal_transaksi', 14, 2)->default(0);

            $table->timestamps();

            // index
            $table->index('ms_settlement_kantin_id');
            $table->index('ms_transaksi_kantin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_settlement_kantin');
    }
};
