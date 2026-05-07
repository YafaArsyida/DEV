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
        Schema::create('ms_akses_kantin', function (Blueprint $table) {
            $table->id('ms_akses_kantin_id');

            $table->unsignedBigInteger('ms_kantin_id')->nullable();
            $table->unsignedBigInteger('ms_pengguna_id')->nullable();

            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_akses_kantin');
    }
};
