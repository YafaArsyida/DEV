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
        Schema::create('ms_kantin', function (Blueprint $table) {
            $table->id('ms_kantin_id');
            $table->unsignedBigInteger('ms_jenjang_id')->nullable();
            $table->string('nama_kantin', 150);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_kantin');
    }
};
