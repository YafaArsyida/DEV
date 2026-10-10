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
        Schema::create('ppdb_periode', function (Blueprint $table) {
            $table->id('ppdb_periode_id');

            $table->unsignedBigInteger('ms_tahun_ajar_id');

            $table->string('nama_periode', 150);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');

            $table->text('deskripsi')->nullable();

            $table->timestamps();
            $table->softDeletes();


            $table->index(
                ['ms_tahun_ajar_id', 'status'],
                'ppdb_periode_tahun_status_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_periode');
    }
};
