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
        Schema::create('ms_tabungan', function (Blueprint $table) {
            $table->id('ms_tabungan_id');
            $table->unsignedBigInteger('ms_jenjang_id');
            $table->unsignedBigInteger('ms_siswa_id'); // Foreign key ke ms_siswa
            $table->unsignedBigInteger('ms_pengguna_id'); // Foreign key ke ms_siswa
            $table->enum('jenis_transaksi', ['setoran', 'penarikan']); // Jenis transaksi
            $table->decimal('nominal', 15, 2); // Nominal transaksi
            $table->timestamp('tanggal'); // Tanggal transaksi
            $table->text('deskripsi')->nullable(); // Catatan tambahan (opsional)
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->softDeletes(); // Kolom deleted_at untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_tabungan');
    }
};
