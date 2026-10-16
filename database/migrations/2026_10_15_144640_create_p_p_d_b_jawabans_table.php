<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ppdb_jawaban', function (Blueprint $table) {
            $table->id('ppdb_jawaban_id');

            $table->unsignedBigInteger('ppdb_periode_id');
            $table->unsignedBigInteger('ppdb_pendaftaran_id');
            $table->unsignedBigInteger('ppdb_pertanyaan_id');

            $table->text('jawaban')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Satu pendaftar hanya memiliki satu jawaban
            // untuk setiap pertanyaan.
            $table->unique(
                ['ppdb_pendaftaran_id', 'ppdb_pertanyaan_id'],
                'ppdb_jawaban_pendaftaran_pertanyaan_unique'
            );

            $table->index(
                ['ppdb_periode_id', 'ppdb_pendaftaran_id'],
                'ppdb_jawaban_periode_pendaftaran_index'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('ppdb_jawaban');
    }
};
