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
        Schema::create('ms_kuitansi_pembayaran', function (Blueprint $table) {
            $table->id('ms_kuitansi_pembayaran_id'); // Primary Key
            $table->string('logo')->nullable(); // Kolom untuk menyimpan foto kop
            $table->string('nama_institusi')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kontak')->nullable();
            $table->string('judul')->nullable();
            $table->string('pesan')->nullable();
            $table->string('tempat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_kuitansi_pembayaran');
    }
};
