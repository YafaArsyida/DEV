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
        Schema::create('ms_saldo_edupay', function (Blueprint $table) {
            $table->bigIncrements('ms_saldo_edupay_id');

            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', ['siswa', 'pegawai']);

            $table->decimal('saldo_edupay', 15, 2)->default(0);

            $table->timestamps();

            // 🔥 Index penting untuk performa
            $table->index(['user_id', 'user_type']);

            // 🔥 Supaya 1 user cuma punya 1 saldo
            $table->unique(['user_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_saldo_edupay');
    }
};
