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
        Schema::create('ppdb_pendidikan', function (Blueprint $table) {
            $table->id('ppdb_pendidikan_id');

            $table->unsignedBigInteger('ppdb_pendaftaran_id')->unique();

            $table->string('asal_sekolah', 200)->nullable();
            $table->string('npsn_asal_sekolah', 20)->nullable();
            $table->string('jenjang_asal', 50)->nullable();

            $table->unsignedSmallInteger('tahun_lulus')->nullable();
            $table->string('nomor_ijazah', 100)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_pendidikan');
    }
};
