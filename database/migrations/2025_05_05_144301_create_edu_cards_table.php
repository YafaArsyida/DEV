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
        Schema::create('ms_educard', function (Blueprint $table) {
            $table->id('ms_educard_id');
            $table->unsignedBigInteger('ms_pengguna_id'); // Relasi ke siswa
            $table->unsignedBigInteger('ms_siswa_id')->nullable(); // Relasi ke siswa
            $table->unsignedBigInteger('ms_pegawai_id')->nullable(); // Relasi ke siswa
            $table->string('kode_kartu', 50)->unique();
            $table->enum('jenis_pemilik', ['pegawai', 'siswa']); // Jenis transaksi
            $table->enum('status_kartu', ['aktif', 'nonaktif', 'diblokir']);
            $table->text('deskripsi')->nullable();
            $table->softDeletes(); // Soft delete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_educard');
    }
};
