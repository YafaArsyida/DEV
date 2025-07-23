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
        Schema::create('ms_pegawai', function (Blueprint $table) {
            $table->id('ms_pegawai_id');
            $table->string('nama_pegawai', 100);
            $table->unsignedBigInteger('ms_jabatan_id'); // Foreign key ke ms_siswa
            $table->string('email', 150)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->text('deskripsi')->nullable(); // Catatan tambahan
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_pegawai');
    }
};
