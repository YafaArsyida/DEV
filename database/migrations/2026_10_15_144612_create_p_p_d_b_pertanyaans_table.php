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
        Schema::create('ppdb_pertanyaan', function (Blueprint $table) {
            $table->id('ppdb_pertanyaan_id');

            $table->unsignedBigInteger('ppdb_periode_id');

            $table->text('pertanyaan');
            $table->string('jenis', 30)->default('teks');
            $table->json('opsi_jawaban')->nullable();

            $table->boolean('wajib_diisi')->default(true);
            $table->unsignedInteger('urutan')->default(0);
            $table->string('status', 20)->default('aktif');

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['ppdb_periode_id', 'status', 'urutan'],
                'ppdb_pertanyaan_periode_status_urutan_index'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('ppdb_pertanyaan');
    }
};
