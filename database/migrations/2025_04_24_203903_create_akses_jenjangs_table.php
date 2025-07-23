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
        Schema::create('ms_akses_jenjang', function (Blueprint $table) {
            $table->id('ms_akses_jenjang_id');
            $table->unsignedBigInteger('ms_pengguna_id');
            $table->unsignedBigInteger('ms_jenjang_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_akses_jenjang');
    }
};
