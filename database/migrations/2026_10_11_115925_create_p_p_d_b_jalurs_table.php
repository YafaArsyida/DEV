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
        Schema::create('ppdb_jalur', function (Blueprint $table) {
            $table->id('ppdb_jalur_id');

            $table->unsignedBigInteger('ppdb_periode_id');

            $table->string('kode_jalur', 30);
            $table->string('nama_jalur', 100);
            $table->text('deskripsi')->nullable();

            $table->unsignedInteger('kuota')->nullable();
            $table->decimal('biaya_pendaftaran', 12, 2)->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['ppdb_periode_id', 'kode_jalur'],
                'ppdb_jalur_periode_kode_unique'
            );

            $table->index(['ppdb_periode_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_jalur');
    }
};
