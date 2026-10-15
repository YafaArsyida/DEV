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
        Schema::create('ppdb_pendaftaran', function (Blueprint $table) {
            $table->id('ppdb_pendaftaran_id');

            // Pemilik pendaftaran
            $table->unsignedBigInteger('user_id');

            // Periode dan gelombang PPDB
            $table->unsignedBigInteger('ppdb_periode_id');
            $table->unsignedBigInteger('ppdb_gelombang_id');

            // Diisi setelah calon siswa dikonversi menjadi siswa resmi
            $table->unsignedBigInteger('ms_siswa_id')->nullable();

            // Identitas pendaftaran
            $table->string('nomor_pendaftaran', 30)->unique();

            // Tahap formulir yang perlu dilanjutkan (2-7)
            $table->unsignedTinyInteger('current_step')->default(2);

            // draft, submitted, under_review, revision,
            // accepted, rejected
            $table->enum('status', ['draft', 'diterima', 'pengecekan', 'perbaikan'])->default('draft');


            // Waktu pengajuan resmi
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index untuk pencarian dan filter admin
            $table->index(['user_id', 'status']);
            $table->index(['ppdb_periode_id', 'status']);
            $table->index(['ppdb_gelombang_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_pendaftaran');
    }
};
