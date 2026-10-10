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
        Schema::create('ppdb_gelombang', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ppdb_periode_id');

            $table->string('nama_gelombang', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->unsignedInteger('kuota')->nullable();

            $table->decimal('biaya_pendaftaran', 12, 2)
                ->default(0);

            // draft, aktif, selesai
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');

            $table->timestamps();

            $table->softDeletes();

            $table->unique(
                ['ppdb_periode_id', 'nama_gelombang'],
                'ppdb_gelombang_periode_nama_unique'
            );

            $table->index(
                ['ppdb_periode_id', 'status'],
                'ppdb_gelombang_periode_status_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_gelombang');
    }
};
