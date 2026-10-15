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
        Schema::create('ppdb_calon_siswa', function (Blueprint $table) {
            $table->id('ppdb_calon_siswa_id');

            $table->unsignedBigInteger('ppdb_pendaftaran_id')->unique();

            $table->string('nama_siswa', 150);
            $table->string('nisn', 20)->nullable();
            $table->string('nik', 20)->nullable();

            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->default('Laki-Laki');

            $table->string('agama', 30)->nullable();
            $table->unsignedTinyInteger('anak_ke')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('nisn');
            $table->index('nik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_calon_siswa');
    }
};
