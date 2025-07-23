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
        Schema::create('ms_edupay', function (Blueprint $table) {
            $table->id('ms_edupay_id'); // Primary Key
            $table->unsignedBigInteger('ms_jenjang_id');
            $table->unsignedBigInteger('ms_siswa_id'); // Relasi ke siswa
            $table->unsignedBigInteger('ms_pengguna_id')->nullable(); // Relasi ke pengguna (opsional)
            $table->enum('jenis_transaksi', ['topup', 'penarikan', 'pembayaran']); // Jenis transaksi
            $table->decimal('nominal', 15, 2); // Nominal transaksi
            $table->date('tanggal'); // Tanggal transaksi
            $table->string('deskripsi')->nullable(); // Deskripsi transaksi
            $table->softDeletes(); // Soft delete
            $table->timestamps(); // Created at dan Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_edupay');
    }
};
