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
        Schema::create('ppdb_alamat', function (Blueprint $table) {
            $table->id('ppdb_alamat_id');

            $table->unsignedBigInteger('ppdb_pendaftaran_id')->unique();

            $table->text('alamat_lengkap')->nullable();

            $table->string('provinsi', 100)->nullable();
            $table->string('kabupaten', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kelurahan_desa', 100)->nullable();

            $table->string('kode_pos', 10)->nullable();
            $table->string('jenis_tempat_tinggal', 50)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_alamat');
    }
};
